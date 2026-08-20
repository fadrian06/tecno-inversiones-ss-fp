<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;

final readonly class AccountSettingsController extends Controller
{
  public function render(string $page): void
  {
    Flight::render("html/vertical-menu-template/pages-account-settings-$page");
  }

  public function update(): void
  {
    $upload = Flight::request()->files['upload'];

    $validatedData = $this->validate([
      'firstName' => 'string',
      'lastName' => 'string',
      'email' => 'email',
    ]);

    if (!auth()->update([
      'name' => $validatedData['firstName'],
      'surname' => $validatedData['lastName'],
      'email' => $validatedData['email'],
      'avatar' => $upload
        ? file_get_contents($upload['tmp_name'])
        : auth()->user()->get()['avatar'],
    ])) {
      flash()->set(auth()->errors());
    }

    Flight::redirect(Flight::request()->url);
  }
}
