<li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar avatar-online">
      <img
        src="<?= auth()->user()->get()['avatar']
          ? ('data:image/png;base64,' . base64_encode(auth()->user()->get()['avatar']))
          : '../../assets/img/avatars/1.png'
        ?>"
        alt
        class="rounded-circle"
      />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="pages-account-settings-account.html">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar avatar-online">
              <img
                src="<?= auth()->user()->get()['avatar']
                  ? ('data:image/png;base64,' . base64_encode(auth()->user()->get()['avatar']))
                  : '../../assets/img/avatars/1.png'
                ?>"
                alt
                class="w-px-40 h-auto rounded-circle"
              />
            </div>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">
              <?= auth()->user()->get()['name'] ?>
              <?= auth()->user()->get()['surname'] ?>
            </h6>
            <small class="text-body-secondary">Administrador</small>
          </div>
        </div>
      </a>
    </li>
    <li>
      <div class="dropdown-divider my-1"></div>
    </li>
    <li>
      <a class="dropdown-item" href="pages-profile-user.html"> <i class="icon-base bx bx-user icon-md me-3"></i><span>Mi Perfil</span> </a>
    </li>
    <li>
      <a class="dropdown-item" href="pages-account-settings-account.html"> <i class="icon-base bx bx-cog icon-md me-3"></i><span>Ajustes</span> </a>
    </li>
    <li>
      <a class="dropdown-item" href="pages-account-settings-billing.html">
        <span class="d-flex align-items-center align-middle">
          <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i><span class="flex-grow-1 align-middle">Plan de Facturación</span>
          <span class="flex-shrink-0 badge rounded-pill bg-danger ms-3">4</span>
        </span>
      </a>
    </li>
    <li>
      <div class="dropdown-divider my-1"></div>
    </li>
    <li>
      <a class="dropdown-item" href="pages-pricing.html"> <i class="icon-base bx bx-dollar icon-md me-3"></i><span>Precios</span> </a>
    </li>
    <li>
      <a class="dropdown-item" href="pages-faq.html"> <i class="icon-base bx bx-help-circle icon-md me-3"></i><span>Preguntas Frecuentes</span> </a>
    </li>
    <li>
      <div class="dropdown-divider my-1"></div>
    </li>
    <li>
      <a class="dropdown-item" href="auth-login-cover.html"> <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Salir</span> </a>
    </li>
  </ul>
</li>
