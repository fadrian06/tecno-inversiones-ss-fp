<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Exception;
use Leaf\Log;
use Override;
use PHPMailer\PHPMailer\PHPMailer;

final readonly class NotifyComingSoonEmails implements BeforeMiddleware
{
  public function __construct(
    private PHPMailer $phpmailer,
    private Log $log,
    private string $subject,
    private string $body,
  ) {}

  #[Override]
  public function before()
  {
    $emails = auth()->db()->select('coming_soon_emails')->fetchAll();

    if (!$emails) {
      return;
    }

    $this->phpmailer->Subject = $this->subject;

    foreach ($emails as ['email' => $email]) {
      try {
        $this->phpmailer->addAddress($email);
        $this->phpmailer->Body = str_replace('[email]', $email, $this->body);
        $this->phpmailer->send();
        $this->phpmailer->clearAddresses();
        auth()->db()->delete('coming_soon_emails')->params(['email' => $email])->execute();
      } catch (Exception $exception) {
        $this->log->critical($this->phpmailer->ErrorInfo, ['exception' => $exception]);
        flash()->set($this->phpmailer->ErrorInfo);
      }
    }
  }
}
