<?php

static $monthNames = [
  '01' => 'enero',
  '02' => 'febrero',
  '03' => 'marzo',
  '04' => 'abril',
  '05' => 'mayo',
  '06' => 'junio',
  '07' => 'julio',
  '08' => 'agosto',
  '09' => 'septiembre',
  '10' => 'octubre',
  '11' => 'noviembre',
  '12' => 'diciembre',
];

$createdAt = new DateTimeImmutable(auth()->user()->get()['created_at']);

?>

<div class="row">
  <div class="col-12">
    <div class="card mb-6">
      <div class="user-profile-header-banner">
        <img src="../../assets/img/pages/profile-banner.png" alt="Banner image" class="rounded-top" />
      </div>
      <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-8">
        <div class="flex-shrink-0 mt-1 mx-sm-0 mx-auto">
          <img
            src="<?= auth()->user()->get()['avatar']
              ? ('data:image/png;base64,' . base64_encode(auth()->user()->get()['avatar']))
              : '../../assets/img/avatars/1.png'
            ?>"
            alt="user image"
            class="d-block h-auto ms-0 ms-sm-6 rounded-3 user-profile-img"
          />
        </div>
        <div class="flex-grow-1 mt-3 mt-lg-5">
          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
            <div class="user-profile-info">
              <h4 class="mb-2 mt-lg-7">
                <?= auth()->user()->get()['name'] ?>
                <?= auth()->user()->get()['surname'] ?>
              </h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 mt-4">
                <li class="list-inline-item"><i class="icon-base bx bx-palette me-2 align-top"></i><span class="fw-medium">UX Designer</span></li>
                <li class="list-inline-item"><i class="icon-base bx bx-map me-2 align-top"></i><span class="fw-medium">Vatican City</span></li>
                <li class="list-inline-item"><i class="icon-base bx bx-calendar me-2 align-top"></i><span class="fw-medium">
                  Se unió en
                  <?= $monthNames[$createdAt->format('m')] ?>
                  de
                  <?= $createdAt->format('Y') ?>
                </span></li>
              </ul>
            </div>
            <a href="javascript:void(0)" class="btn btn-primary mb-1"> <i class="icon-base bx bx-user-check icon-sm me-2"></i>Conectado </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
