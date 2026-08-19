<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flight;
use Leaf\Helpers\Password;
use PHPMailer\PHPMailer\PHPMailer;

final readonly class ForgotPasswordController extends Controller
{
  public function __construct(private PHPMailer $phpmailer) {}

  public function render(): void
  {
    Flight::render('html/vertical-menu-template/auth-forgot-password-basic');
  }

  public function sendCode(): void
  {
    $validatedData = $this->validate(['email' => 'email']);

    $this->phpmailer->addAddress($validatedData['email']);
    $this->phpmailer->Subject = 'Recuperación de contraseña';
    $hash = Password::hash(session()->id());
    session()->set('expiration', time() + (60 * 60 * 24));
    session()->set('email', $validatedData['email']);

    $this->phpmailer->Body = <<<html
      <h1>
        Asunto:
        <span style="font-weight: normal">
          Restablece tu contraseña de
          <code>{$_ENV['APP_NAME']}</code>
        </span>
        🔐
      </h1>
      <p>Hola, <code>{$validatedData['email']}</code></p>
      <p>
        Recibimos una solicitud para restablecer la contraseña de tu cuenta. No
        te preocupes, puedes volver a ingresar fácilmente haciendo clic en el
        siguiente enlace:
      </p>
      <a href="{$_ENV['APP_URL']}/html/vertical-menu-template/auth-reset-password-basic.html?hash=$hash">
        Restablecer mi contraseña
      </a>
      <p><em>Este enlace expirará en 24 horas por razones de seguridad.</em></p>
      <p>
        Si tú no realizaste esta solicitud, puedes ignorar este correo de forma
        segura. Tu contraseña actual seguirá siendo la misma y nadie más tiene
        acceso a tu cuenta.
      </p>
      <footer>El equipo de <code>{$_ENV['APP_NAME']}</code></footer>
    html;

    $this->phpmailer->send();
    Flight::redirect(Flight::request()->url);
  }
}
