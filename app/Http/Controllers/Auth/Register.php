<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvokableController;
use Flight;
use Override;

final readonly class Register extends Controller implements InvokableController
{
  #[Override]
  public function __invoke()
  {
    $validatedData = $this->validate([
      'username' => 'username|min:6',
      'email' => 'email',
      'password' => 'min:6',
      'terms' => 'boolean',
    ]);

    if (auth()->register([
      'username' => $validatedData['username'],
      'email' => $validatedData['email'],
      'password' => $validatedData['password'],
    ])) {
      return Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');
    }

    flash()->set(auth()->errors());
    Flight::redirect(Flight::request()->url);
  }
}
