<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;
use Leaf\Auth\Model;

final readonly class EcommerceSettingsController extends Controller
{
  public function render(string $page): void
  {
    Flight::render("html/vertical-menu-template/app-ecommerce-settings-$page");
  }

  public function update(): void
  {
    $validatedData = $this->validate([
      'pk' => 'optional|string',
      'settingsDet' => 'string',
      'phone' => 'phone',
      'business-name' => 'matchesvalueof<settingsDet>',
      'bill_address' => 'string',
    ]);

    $business = auth()->user()->business();

    if (!$business instanceof Model) {
      return;
    }

    $data = [
      'name' => $validatedData['settingsDet'],
      'address' => $validatedData['bill_address'],
    ];

    if (array_key_exists('pk', $validatedData)) {
      if (
        !$business
          ->update($data)
          ->where('name', $validatedData['pk'])
          ->execute()
      ) {
        flash()->set(auth()->db()->errors());
        Flight::redirect(Flight::request()->url);

        return;
      }
    } elseif (!$business->create($data)) {
      flash()->set(auth()->db()->errors());
      Flight::redirect(Flight::request()->url);

      return;
    }

    auth()
      ->db()
      ->delete('business_phones')
      ->where('business_name', $validatedData['settingsDet'])
      ->execute();

    if (
      !auth()
        ->db()
        ->insert('business_phones')
        ->params([
          'business_name' => $validatedData['settingsDet'],
          'phone' => $validatedData['phone'],
        ])
        ->unique('phone')
        ->execute()
    ) {
      flash()->set(auth()->db()->errors());
      Flight::redirect(Flight::request()->url);

      return;
    }

    session()->set('business', [
      'name' => $validatedData['settingsDet'],
      'address' => $validatedData['bill_address'],
      'phones' => [$validatedData['phone']],
    ]);

    Flight::redirect(Flight::request()->url);
  }
}
