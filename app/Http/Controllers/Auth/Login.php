<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvokableController;
use Flight;
use Override;

final readonly class Login extends Controller implements InvokableController
{
  #[Override]
  public function __invoke()
  {
    $validatedData = $this->validate([
      'email-username' => 'email-username',
      'password' => 'min:6',
      'remember-me' => 'optional|boolean',
    ]);

    $validatedData['remember-me'] = filter_var(
      $validatedData['remember-me'] ?? false,
      FILTER_VALIDATE_BOOLEAN,
    );

    $credentials = [auth()->config('password.key') => $validatedData['password']];

    if (form()->isEmail($validatedData['email-username'])) {
      $credentials['email'] = $validatedData['email-username'];
    } else {
      $credentials['username'] = $validatedData['email-username'];
    }

    if ($validatedData['remember-me']) {
      auth()->config('session.lifetime', 0);
    }

    if (auth()->login($credentials)) {
      return Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');
    }

    flash()->set(auth()->errors());
    Flight::redirect(Flight::request()->url);
  }
}
