<?php

declare(strict_types=1);

$userIdKey = auth()->config('id.key');
$userTable = auth()->config('db.table');

auth()
  ->db()
  ->createTableIfNotExists('business', [
    "user_$userIdKey" => "INTEGER NOT NULL REFERENCES $userTable($userIdKey)",
    'name' => 'TEXT PRIMARY KEY',
    'address' => 'TEXT NOT NULL UNIQUE',
    'created_at' => 'TEXT NOT NULL UNIQUE DEFAULT CURRENT_TIMESTAMP',
    'updated_at' => 'TEXT NOT NULL UNIQUE DEFAULT CURRENT_TIMESTAMP CHECK (updated_at >= created_at)',
  ])
  ->execute();

auth()
  ->db()
  ->createTableIfNotExists('business_phones', [
    'business_name' => 'TEXT NOT NULL REFERENCES business(name) ON UPDATE CASCADE',
    'phone' => 'TEXT NOT NULL UNIQUE',
  ])->execute();
