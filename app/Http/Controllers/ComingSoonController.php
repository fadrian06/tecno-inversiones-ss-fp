<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;

final readonly class ComingSoonController extends Controller
{
  public function render(): void
  {
    Flight::render('html/vertical-menu-template/pages-misc-comingsoon');
  }

  public function subscribe(): void
  {
    $validatedData = $this->validate(['email' => 'email']);

    if (
      !auth()
        ->db()
        ->insert('coming_soon_emails')
        ->unique('email')
        ->params($validatedData)
        ->execute()
    ) {
      flash()->set(auth()->db()->errors());
    }

    Flight::redirect(Flight::request()->url);
  }
}
