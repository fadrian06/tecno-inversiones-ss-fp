<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\{Login, Logout, OAuth2Controller, Register};
use App\Http\Controllers\ComingSoonController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\{Authenticate, NotifyComingSoonEmails, RedirectIfAuthenticated};
use flight\Container;
use Leaf\Log;
use PHPMailer\PHPMailer\PHPMailer;

Flight::route('GET /', static function (): void {})
  ->addMiddleware(Authenticate::class)
  ->addMiddleware(RedirectIfAuthenticated::class);

Flight::group('/html/vertical-menu-template', static function (): void {
  Flight::route('GET /index.html', static function (): void {
    Flight::render('index');
  })->addMiddleware(Authenticate::class);

  Flight::route('GET /app-ecommerce-dashboard.html', static function (): void {
    Flight::render('app-ecommerce-dashboard');
  })->addMiddleware(Authenticate::class);

  Flight::group('/auth-login-basic.html', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::render('auth-login-basic');
    });

    Flight::route('POST /', [Login::class, '__invoke']);
  }, [RedirectIfAuthenticated::class]);

  Flight::group('/auth-register-basic.html', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::render('auth-register-basic');
    });

    Flight::route('POST /', [Register::class, '__invoke']);
  }, [RedirectIfAuthenticated::class]);

  Flight::route('GET /auth-login-cover.html', [Logout::class, '__invoke']);

  Flight::group('/auth-forgot-password-basic.html', static function (): void {
    Flight::route('GET /', [ForgotPasswordController::class, 'render']);
    Flight::route('POST /', [ForgotPasswordController::class, 'sendCode']);
  }, [RedirectIfAuthenticated::class]);

  Flight::group('/auth-reset-password-basic.html', static function (): void {
    Flight::route('GET /', [ResetPasswordController::class, 'render']);
    Flight::route('POST /', [ResetPasswordController::class, 'resetPassword']);
  }, [RedirectIfAuthenticated::class]);

  Flight::group('/pages-misc-comingsoon.html', static function (): void {
    Flight::route('GET /', [ComingSoonController::class, 'render']);
    Flight::route('POST /', [ComingSoonController::class, 'subscribe']);
  });
});

Flight::group('/oauth2', static function (): void {
  Flight::route('GET /facebook', [OAuth2Controller::class, 'facebook'])
    ->setAlias('oauth2.facebook')
    ->addMiddleware(new NotifyComingSoonEmails(
      Container::getInstance()->get(PHPMailer::class),
      Container::getInstance()->get(Log::class),
      '¡Ya puedes iniciar sesión con Facebook!',
      <<<html
        <h1>
          Asunto:
          <span style="font-weight: normal">
            ¡Ya puedes iniciar sesión con Facebook! 🚀
          </span>
        </h1>
        <p>Hola, <code>[email]</code></p>
        <p>
          Tenemos excelentes noticias. A partir de hoy, acceder a nuestra
          plataforma es mucho más rápido y sencillo.
        </p>
        <p>
          Hemos añadido la opción de <strong>Iniciar sesión con Facebook</strong>.
          Ya no necesitas recordar una contraseña más; ahora puedes entrar con un
          solo clic.
        </p>
        <h2>¿Cómo empezar?</h2>
        <ol>
          <li>Ve a nuestra página de inicio de sesión.</li>
          <li>Haz clic en el botón de "Facebook".</li>
          <li>¡Listo! Ya estás dentro.</li>
        </ol>
        <p>
          Si ya tenías una cuenta con nosotros usando el mismo correo de tu
          Facebook, ambas cuentas se vincularán automáticamente sin perder tu
          información.
        </p>
        <a href="{$_ENV['APP_URL']}/html/vertical-menu-template/auth-login-basic.html">
          Inicia sesión ahora
        </a>
        <p>
          Si tienes alguna pregunta o inconveniente para ingresar, responde a
          este correo y nuestro equipo te ayudará de inmediato.
        </p>
        <footer>El equipo de {$_ENV['APP_NAME']}</footer>
      html,
    ));

  Flight::route('GET /twitter', [OAuth2Controller::class, 'twitter'])->setAlias('oauth2.twitter')
    ->addMiddleware(new NotifyComingSoonEmails(
      Container::getInstance()->get(PHPMailer::class),
      Container::getInstance()->get(Log::class),
      '¡Ya puedes iniciar sesión con Twitter!',
      <<<html
        <h1>
          Asunto:
          <span style="font-weight: normal">
            ¡Ya puedes iniciar sesión con Twitter! 🚀
          </span>
        </h1>
        <p>Hola, <code>[email]</code></p>
        <p>
          Tenemos excelentes noticias. A partir de hoy, acceder a nuestra
          plataforma es mucho más rápido y sencillo.
        </p>
        <p>
          Hemos añadido la opción de <strong>Iniciar sesión con Twitter</strong>.
          Ya no necesitas recordar una contraseña más; ahora puedes entrar con un
          solo clic.
        </p>
        <h2>¿Cómo empezar?</h2>
        <ol>
          <li>Ve a nuestra página de inicio de sesión.</li>
          <li>Haz clic en el botón de "Twitter".</li>
          <li>¡Listo! Ya estás dentro.</li>
        </ol>
        <p>
          Si ya tenías una cuenta con nosotros usando el mismo correo de tu
          Twitter, ambas cuentas se vincularán automáticamente sin perder tu
          información.
        </p>
        <a href="{$_ENV['APP_URL']}/html/vertical-menu-template/auth-login-basic.html">
          Inicia sesión ahora
        </a>
        <p>
          Si tienes alguna pregunta o inconveniente para ingresar, responde a
          este correo y nuestro equipo te ayudará de inmediato.
        </p>
        <footer>El equipo de {$_ENV['APP_NAME']}</footer>
      html,
    ));

  Flight::route('GET /github', [OAuth2Controller::class, 'github'], true)->setAlias('oauth2.github');
  Flight::route('GET /google', [OAuth2Controller::class, 'google'], true)->setAlias('oauth2.google');
}, [RedirectIfAuthenticated::class]);
