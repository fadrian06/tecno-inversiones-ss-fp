<?php

enum Link: string
{
  case Profile = 'user';
  case Teams = 'teams';
  case Projects = 'projects';
  case Connections = 'connections';

  public function getBoxiconsIconName(): string
  {
    return match ($this) {
      self::Profile => 'user',
      self::Teams => 'group',
      self::Projects => 'grid-alt',
      self::Connections => 'link',
    };
  }

  public function getLabel(): string
  {
    return match ($this) {
      self::Profile => 'Perfil',
      self::Teams => 'Equipos',
      self::Projects => 'Proyectos',
      self::Connections => 'Conexiones',
    };
  }
}

$activeLink = Link::from($activeLink ?? '');

?>

<div class="row">
  <div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
        <?php foreach (Link::cases() as $link): ?>
          <li class="nav-item">
            <a
              class="nav-link <?= $link === $activeLink ? 'active' : '' ?>"
              href="<?= $link === $activeLink
                ? 'javascript:void(0);'
                : "pages-profile-$link->name.html"
              ?>">
              <i class="icon-base bx bx-<?= $link->getBoxiconsIconName() ?> icon-sm me-1_5"></i>
              <?= $link->getLabel() ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    </div>
  </div>
</div>
