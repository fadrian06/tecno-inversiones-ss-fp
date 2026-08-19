<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Log;
use Leaf\LogWriter;
use League\OAuth2\Client\Provider\Github;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/vendor/autoload.php';

if (file_exists($filename = __DIR__ . '/.env.php')) {
  $_ENV = require $filename;
}

$_ENV += require __DIR__ . '/.env.example.php';

auth()->config('messages.loginParamsError', '¡Credenciales incorrectas!');
auth()->config('messages.loginPasswordError', '¡Contraseña incorrecta!');
auth()->config('session', true);

auth()->withProvider('github', new Github([
  'clientId' => $_ENV['GITHUB_CLIENT_ID'],
  'clientSecret' => $_ENV['GITHUB_CLIENT_SECRET'],
  'redirectUri' => $_ENV['GITHUB_REDIRECT_URI'],
]));

auth()->autoConnect();

foreach (glob(__DIR__ . '/database/migrations/*.php') as $migration) {
  require_once $migration;
}

form()->addRule(
  'email-username',
  static function (
    string $value,
    array $param,
    string $fieldName,
    array $dataSource,
  ): bool {
    return form()->isEmail($value) || form()->validate($dataSource, [$fieldName => 'username|min:6']);
  },
  '{Field} debe ser un correo electrónico válido o un nombre de usuario (mínimo 6 caracteres).',
);

form()->addRule(
  'boolean',
  '/^(true|false|1|0|on|off)$/',
  '{Field} debe ser un valor booleano (true, false, 1, 0, on, off).',
);

Container::getInstance()->singleton(Log::class, static fn(): Log => new Log(new LogWriter(
  __DIR__ . '/storage/logs/flight.log',
  true,
)));

Container::getInstance()->singleton(PHPMailer::class, static function (): PHPMailer {
  $phpmailer = new PHPMailer;
  $phpmailer->isSMTP();
  $phpmailer->Host = $_ENV['PHPMAILER_HOST'];
  $phpmailer->SMTPAuth = true;
  $phpmailer->Username = $_ENV['PHPMAILER_USERNAME'];
  $phpmailer->Password = $_ENV['PHPMAILER_PASSWORD'];
  $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $phpmailer->Port = 465;
  $phpmailer->setFrom($_ENV['PHPMAILER_USERNAME'], $_ENV['APP_NAME']);
  $phpmailer->isHTML(true);
  $phpmailer->CharSet = 'UTF-8';

  return $phpmailer;
});

Flight::set('flight.handle_errors', false);
Flight::set('flight.views.path', __DIR__);
Flight::registerContainerHandler(Container::getInstance());

require_once __DIR__ . '/routes/web.php';

Flight::start();
