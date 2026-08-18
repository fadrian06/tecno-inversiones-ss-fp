<?php

declare(strict_types=1);

namespace App\Http\Middleware;

interface BeforeMiddleware
{
  /** @return void|false */
  public function before();
}
