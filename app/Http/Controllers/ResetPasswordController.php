<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;
use Leaf\Helpers\Password;

final readonly class ResetPasswordController extends Controller
{
  public function render(): void
  {
    $this->validateHash();
    Flight::render('auth-reset-password-basic');
  }

  public function resetPassword(): void
  {
    $this->validateHash();

    $validatedData = $this->validate([
      'password' => 'min:6',
      'confirm-password' => 'matchesvalueof<password>',
    ]);

    auth()
      ->db()
      ->update(auth()->config('db.table'))
      ->params([auth()->config('password.key') => Password::hash($validatedData['password'])])
      ->execute();

    if (auth()->db()->errors()) {
      flash()->set(auth()->db()->errors());
      Flight::redirect(Flight::request()->url);

      return;
    }

    if (!auth()->login([
      'email' => session()->get('email'),
      auth()->config('password.key') => $validatedData['password'],
    ])) {
      flash()->set(auth()->errors());
      Flight::redirect(Flight::request()->url);

      return;
    }

    session()->remove('expiration');
    session()->remove('email');
    Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');
  }

  private function validateHash(): void
  {
    $hash = Flight::request()->query['hash'];

    if (!Password::verify(session()->id(), $hash)) {
      Flight::redirect('/html/vertical-menu-template/auth-forgot-password-basic.html');

      return;
    }
  }
}
