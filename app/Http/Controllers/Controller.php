<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;

abstract readonly class Controller
{
  final protected function validate(array $validationSet, string $redirectUri = ''): array
  {
    $validatedData = form()->validate(Flight::request()->data->getData(), $validationSet);

    if (!$validatedData) {
      flash()->set(form()->errors());
      Flight::redirect($redirectUri ?: Flight::request()->url);

      exit;
    }

    return $validatedData;
  }
}
