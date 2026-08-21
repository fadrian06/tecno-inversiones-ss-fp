<?php

enum Link: string
{
  case Details = 'details';
  case Payments = 'payments';
  case Checkout = 'checkout';
  case Shipping = 'shipping';
  case Locations = 'locations';
  case Notifications = 'notifications';

  public function getBoxiconsIconName(): string
  {
    return match ($this) {
      self::Details => 'store-alt',
      self::Payments => 'credit-card',
      self::Checkout => 'cart',
      self::Shipping => 'package',
      self::Locations => 'map',
      self::Notifications => 'bell',
    };
  }

  public function getLabel(): string
  {
    return match ($this) {
      self::Details => 'Detalles de la Tienda',
      self::Payments => 'Pagos',
      self::Checkout => 'Caja',
      self::Shipping => 'Envíos & Entregas',
      self::Locations => 'Ubicaciones',
      self::Notifications => 'Notificaciones',
    };
  }
}

$activeLink = Link::from($activeLink ?? '');

?>

<div class="col-12 col-lg-4">
  <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
    <h5 class="mb-4">Primeros Pasos</h5>
    <ul class="nav nav-align-left nav-pills flex-column">
      <?php foreach (Link::cases() as $link): ?>
        <li class="nav-item mb-1">
          <a
            class="nav-link <?= $link === $activeLink ? 'active' : '' ?>"
            href="<?= $link === $activeLink
              ? 'javascript:void(0);'
              : "app-ecommerce-settings-$link->value.html"
            ?>">
            <i class="icon-base bx bx-<?= $link->getBoxiconsIconName() ?> icon-18px me-1_5"></i>
            <span class="align-middle"><?= $link->getLabel() ?></span>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
</div>
