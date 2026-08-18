<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Flight;
use Override;

final readonly class RedirectIfAuthenticated implements BeforeMiddleware
{
  #[Override]
  public function before()
  {
    if (auth()->user()) {
      Flight::redirect('/html/vertical-menu-template/app-ecommerce-dashboard.html');
    }
  }
}
