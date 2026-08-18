<?php

declare(strict_types=1);

return [
  'DB_CONNECTION' => 'sqlite',
  'DB_DATABASE' => __DIR__ . '/database/database.sqlite',

  // https://github.com/settings/applications/new
  'GITHUB_CLIENT_ID' => '{github-client-id}',
  'GITHUB_CLIENT_SECRET' => '{github-client-secret}',
  'GITHUB_REDIRECT_URI' => 'https://example.com/callback-url',

  // https://console.cloud.google.com/auth
  'GOOGLE_AUTH_CLIENT_ID' => '{google-client-id}',
  'GOOGLE_AUTH_CLIENT_SECRET' => '{google-client-secret}',
  'GOOGLE_AUTH_REDIRECT_URI' => 'https://example.com/callback-url',

  'APP_NAME' => 'Tecno Inversiones S.S. F.P.',
  'APP_URL' => 'http://localhost',

  // https://myaccount.google.com/apppasswords
  'PHPMAILER_HOST' => 'smtp.gmail.com',
  'PHPMAILER_USERNAME' => '{gmail-username}@gmail.com',
  'PHPMAILER_PASSWORD' => '{phpmailer-password}',
];
