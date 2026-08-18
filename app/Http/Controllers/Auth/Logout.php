<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\InvokableController;
use Flight;
use Override;

final readonly class Logout implements InvokableController
{
  #[Override]
  public function __invoke()
  {
    auth()->logout();
    Flight::redirect('/html/vertical-menu-template/auth-login-basic.html');
  }
}
