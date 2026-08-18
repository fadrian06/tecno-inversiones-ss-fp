<?php

declare(strict_types=1);

namespace App\Http\Controllers;

interface InvokableController
{
  /** @return void|true */
  public function __invoke();
}
