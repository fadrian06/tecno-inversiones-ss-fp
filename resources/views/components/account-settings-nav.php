<?php

enum Link: string
{
  case Account = 'account';
  case Security = 'security';
  case Billing = 'billing';
  case Notifications = 'notifications';
  case Connections = 'connections';

  public function getBoxiconsIconName(): string
  {
    return match ($this) {
      self::Account => 'user',
      self::Security => 'lock-alt',
      self::Billing => 'detail',
      self::Notifications => 'bell',
      self::Connections => 'link-alt',
    };
  }

  public function getLabel(): string
  {
    return match ($this) {
      self::Account => 'Cuenta',
      self::Security => 'Seguridad',
      self::Billing => 'Facturación & Planes',
      self::Notifications => 'Notificaciones',
      self::Connections => 'Conexiones',
    };
  }
}

$activeLink = Link::from($activeLink ?? '');

?>

<div class="nav-align-top">
  <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
    <?php foreach (Link::cases() as $link): ?>
      <li class="nav-item">
        <a
          class="nav-link <?= $link === $activeLink ? 'active' : '' ?>"
          href="<?= $link === $activeLink
            ? 'javascript:void(0);'
            : "pages-account-settings-$link->value.html"
          ?>">
          <i class="icon-base bx bx-<?= $link->getBoxiconsIconName() ?> icon-sm me-1_5"></i>
          <?= $link->getLabel() ?>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</div>
