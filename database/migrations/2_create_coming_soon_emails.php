<?php

declare(strict_types=1);

auth()
  ->db()
  ->createTableIfNotExists('coming_soon_emails', [
    'email' => 'TEXT PRIMARY KEY',
  ])
  ->execute();
