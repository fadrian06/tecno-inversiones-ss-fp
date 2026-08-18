<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Exception;
use Flight;
use flight\net\Route;
use Leaf\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;

final readonly class OAuth2Controller
{
  public function __construct(private Log $log) {}

  public function facebook(): void
  {
    Flight::redirect('/html/vertical-menu-template/pages-misc-comingsoon.html');
  }

  public function twitter(): void
  {
    Flight::redirect('/html/vertical-menu-template/pages-misc-comingsoon.html');
  }

  public function github(Route $route): void
  {
    $provider = auth()->client('github');
    $code = Flight::request()->query['code'];
    $state = Flight::request()->query['state'];

    $this->setReferer($route);

    if (!$code) {
      // If we don't have an authorization code then get one
      $authUrl = $provider->getAuthorizationUrl();
      session()->set('oauth2state', $provider->getState());
      Flight::redirect($authUrl);

      return;
    }

    // Check given state against previously stored one to mitigate CSRF attack
    if (!$state || $state !== session()->get('oauth2state')) {
      session()->remove('oauth2state');
      flash()->set('Estado inválido');
      $this->redirectToRefererOrLogin('Estado inválido');

      return;
    }

    // Try to get an access token (using the authorization code grant)
    try {
      $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
    } catch (IdentityProviderException $exception) {
      $this->redirectToRefererOrLogin($exception->getMessage());

      return;
    }

    // Optional: Now you have a token you can look up a users profile data
    try {
      // We got an access token, let's now get the user's details
      $user = $provider->getResourceOwner($token);

      if (!$user instanceof GithubResourceOwner) {
        return;
      }

      // Use these details to create a new profile
      if (auth()->fromOAuth([
        'token' => $token,
        'user' => ['email' => $user->getEmail()],
      ])) {
        $userData = [];

        if (!auth()->user()->get()['username']) {
          $userData['username'] = $user->getName();
        }

        if (!auth()->user()->get()['avatar']) {
          $userData['avatar'] = file_get_contents($user->toArray()['avatar_url']);
        }

        if ($userData) {
          auth()->update($userData);
        }

        Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');

        return;
      }

      flash()->set(auth()->errors());
      $this->redirectToRefererOrLogin(auth()->errors());
    } catch (Exception $exception) {
      // Failed to get user details
      $this->log->critical('Oh dear...', ['exception' => $exception]);
      $this->redirectToRefererOrLogin('Oh dear...');
    }
  }

  public function google(Route $route): void
  {
    $provider = auth()->client('google');
    $error = Flight::request()->query['error'];
    $code = Flight::request()->query['code'];
    $state = Flight::request()->query['state'];

    $this->setReferer($route);

    if ($error) {
      // Got an error, probably user denied access
      $this->redirectToRefererOrLogin($error);

      return;
    }

    if (!$code) {
      // If we don't have an authorization code then get one
      $authUrl = $provider->getAuthorizationUrl();
      session()->set('oauth2state', $provider->getState());
      Flight::redirect($authUrl);

      return;
    }

    if (!$state || $state !== session()->get('oauth2state')) {
      // State is invalid, possible CSRF attack in progress
      session()->remove('oauth2state');
      $this->redirectToRefererOrLogin('Estado inválido');

      return;
    }

    // Try to get an access token (using the authorization code grant)
    try {
      $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
    } catch (IdentityProviderException $exception) {
      $this->redirectToRefererOrLogin($exception->getMessage());

      return;
    }

    // Optional: Now you have a token you can look up a users profile data
    try {
      // We got an access token, let's now get the owner details
      $ownerDetails = $provider->getResourceOwner($token);

      if (!$ownerDetails instanceof GoogleUser) {
        return;
      }

      // Use these details to create a new profile
      if (auth()->fromOAuth([
        'token' => $token,
        'user' => ['email' => $ownerDetails->getEmail()],
      ])) {
        $userData = [];

        if (!auth()->user()->get()['avatar']) {
          $userData['avatar'] = file_get_contents($ownerDetails->getAvatar());
        }

        if (!auth()->user()->get()['name']) {
          $userData['name'] = $ownerDetails->getFirstName();
        }

        if (!auth()->user()->get()['surname']) {
          $userData['surname'] = $ownerDetails->getLastName();
        }

        if ($userData) {
          auth()->update($userData);
        }

        Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');

        return;
      }

      $this->redirectToRefererOrLogin(auth()->errors());
    } catch (Exception $exception) {
      // Failed to get user details
      $this->log->critical('Something went wrong', ['exception' => $exception]);
      $this->redirectToRefererOrLogin("Something went wrong: {$exception->getMessage()}");
    }
  }

  private function setReferer(Route $route): void
  {
    if (!session()->has('referer') && str_contains(Flight::request()->url, $route->pattern)) {
      session()->set('referer', Flight::request()->url);
    }
  }

  private function redirectToRefererOrLogin(mixed $message): void
  {
    flash()->set($message);
    Flight::redirect(session()->retrieve('referer', '/html/vertical-menu-template/auth-login-basic.html'));
  }
}
