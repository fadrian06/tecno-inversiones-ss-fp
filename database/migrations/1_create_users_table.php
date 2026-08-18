<?php

declare(strict_types=1);

$passwordKey = auth()->config('password.key');

auth()
  ->db()
  ->createTableIfNotExists(auth()->config('db.table'), [
    auth()->config('id.key') => 'INTEGER PRIMARY KEY AUTOINCREMENT',
    'email' => 'TEXT UNIQUE CHECK (email LIKE "%@%")',
    'username' => 'TEXT UNIQUE CHECK (LENGTH(username) >= 6)',
    $passwordKey => "TEXT UNIQUE CHECK (LENGTH($passwordKey) >= 6)",
    'avatar' => 'BLOB UNIQUE',
    'created_at' => 'TEXT NOT NULL UNIQUE DEFAULT CURRENT_TIMESTAMP',
    'updated_at' => 'TEXT NOT NULL UNIQUE DEFAULT CURRENT_TIMESTAMP CHECK (updated_at >= created_at)',
    'name' => 'TEXT',
    'surname' => 'TEXT',
  ])
  ->execute();
