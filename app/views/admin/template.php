<?php

use HS\config\enums\SubDomains;
use HS\libs\core\Session;
use HS\libs\helpers\HTML;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Path;
use HS\libs\io\Url;
use HS\libs\view\View;

$viewLayout = View::GetLayout();
$viewSections = View::GetLayout()->Sections;

$viewData = View::GetData();
$circle_name = urlencode(isset($viewData->CurrentCircle) ? $viewData->CurrentCircle->Name : '');
$circle_title = htmlspecialchars(isset($viewData->CurrentCircle) ? $viewData->CurrentCircle->Title : '');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= empty($viewLayout->Title) ? '' : "$viewLayout->Title | " ?>Creation Panel | HunterStars</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <meta name="description"
          content="HunterStars, ¡un sitio donde encuentras de todo! Musica, Anime, Juegos, Novelas, Mangas."/>
    <meta name="keywords" content="HunterStars Novelas Anime Juegos Mangas Musica Japón"/>
    <meta name="author" content="HunterStars"/>

    <!-- Favicon icon -->
    <link rel="icon" href="/files/icon/favicon.ico" type="image/x-icon">

    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!--[if lt IE 11]><?= HTML::VendorScripts(['html5shiv', 'respond.min']); ?><![endif]-->

    <!-- FONT CSS -->
    <?= HTML::VendorFonts([
        'tabler-icons.min',
        'feather',
        'fontawesome',
        'material'
    ]); ?>
    <!-- End FONT CSS -->

    <!-- Styles -->
    <?= $viewLayout->GetVendorStyles() ?>
    <link rel="stylesheet" href="<?= UrlFiles::GetCSS('style-dark') ?>" id="main-style-link">
    <?= HTML::Styles('customizer'); ?>
    <?= HTML::Styles('studio'); ?>
    <?= $viewLayout->GetStyles() ?>
    <!-- End Styles -->
</head>
<body class="">
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->
<!-- [ Mobile header ] start -->
<div class="pc-mob-header pc-header">
    <div class="pcm-logo">
        <img src="/files/img/basic/hs-text.png?w=160" alt="" class="logo logo-lg">
    </div>
    <div class="pcm-toolbar">
        <a href="#" class="pc-head-link" id="mobile-collapse">
            <div class="hamburger hamburger--arrowturn">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
            <!-- <i data-feather="menu"></i> -->
        </a>
        <a href="#" class="pc-head-link" id="headerdrp-collapse">
            <i data-feather="align-right"></i>
        </a>
        <a href="#" class="pc-head-link" id="header-collapse">
            <i data-feather="more-vertical"></i>
        </a>
    </div>
</div>
<!-- [ Mobile header ] End -->

<?php if ($viewLayout->ShowLateralMenu): ?>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="<?= UrlFiles::GetRoot(SubDomains::root) ?>" class="b-brand">
                    <img src="/files/img/basic/hs-text.png?h=30" alt="" class="logo logo-lg">
                    <img src="/files/img/basic/hs-text.png?h=30" alt="" class="logo logo-sm">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Círculos</label>
                    </li>
                    <li class="pc-item" style="display: block">
                        <a href="/" class="pc-link ">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">group_work</i>
                        </span>
                            <span class="pc-mtext">Cambiar circulo</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Contenido</label>
                        <span>Extra</span>
                    </li>
                    <li class="pc-item">
                        <a href="<?= Url::Combine('/', $circle_name) ?>" class="pc-link"><span class="pc-micon"><i
                                        class="material-icons-two-tone">bar_chart</i></span><span
                                    class="pc-mtext">Panel</span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="<?= Url::Combine('/', $circle_name, 'pages') ?>" class="pc-link ">
                            <span class="pc-micon"><i class="material-icons-two-tone">chrome_reader_mode</i></span>
                            <span class="pc-mtext">Paginas</span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                    <li class="pc-item disabled">
                        <a href="#" class="pc-link"><span class="pc-micon"><i
                                        class="material-icons-two-tone">comment</i></span><span
                                    class="pc-mtext">Comentarios</span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Configuración</label>
                    </li>
                    <li class="pc-item">
                        <a href="<?= Url::Combine('/', $circle_name, 'settings') ?>" class="pc-link"><span class="pc-micon"><i
                                        class="material-icons-two-tone">assignment</i></span><span
                                    class="pc-mtext">Datos generales</span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                    <li class="pc-item disabled">
                        <a href="#" class="pc-link"><span class="pc-micon"><i
                                        class="material-icons-two-tone">people</i></span><span
                                    class="pc-mtext" disabled="">Miembros</span>
                        </a>
                        <ul class="pc-submenu">
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>

<!-- [ Header ] start -->
<header class="pc-header ">
    <div class="header-wrapper">
        <?php if (!$viewLayout->ShowLateralMenu): ?>
            <div class="pcm-logo me-4">
                <img src="/files/img/basic/hs-text.png?w=160" alt="" class="logo logo-sm">
            </div>
        <?php endif; ?>
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item">
                    <a class="pc-head-link active arrow-none me-0"
                       href="<?= UrlFiles::GetRoot(SubDomains::root) ?>"
                       role="button">
                        Regresar al sitio principal
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="material-icons-two-tone">search</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label data-feather="search"></label>
                                <input type="search" class="form-control border-0 shadow-none"
                                       placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li>
                <?php if ($viewLayout->ShowNotificationPanel): ?>
                    <li class="pc-h-item">
                        <a class="pc-head-link me-0" href="#" data-bs-toggle="modal"
                           data-bs-target="#notification-modal">
                            <i class="material-icons-two-tone">notifications_active</i>
                            <span class="bg-danger pc-h-badge dots"><span class="sr-only"></span></span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="/files/img/basic/Errdex.png" alt="user-image" class="user-avtar">
                        <span>
								<span class="user-name"><?= htmlspecialchars(Session::Get()->User->GetShortName()) ?></span>
								<span class="user-desc">Usuario Normal</span>
							</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="/logout" class="dropdown-item">
                            <i class="material-icons-two-tone">power_settings_new</i>
                            <span>Cerrar sesión</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</header>

<?php if ($viewLayout->ShowNotificationPanel): ?>
    <div class="modal notification-modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    <ul class="nav nav-pill tabs-light mb-3" id="pc-noti-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pc-noti-home-tab" data-bs-toggle="pill" href="#pc-noti-home"
                               role="tab" aria-controls="pc-noti-home" aria-selected="true">Notification</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pc-noti-news-tab" data-bs-toggle="pill" href="#pc-noti-news"
                               role="tab" aria-controls="pc-noti-news" aria-selected="false">News<span
                                        class="badge bg-danger ms-2 d-none d-sm-inline-block">4</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pc-noti-settings-tab" data-bs-toggle="pill" href="#pc-noti-settings"
                               role="tab" aria-controls="pc-noti-settings" aria-selected="false">Setting<span
                                        class="badge bg-success ms-2 d-none d-sm-inline-block">Update</span></a>
                        </li>
                    </ul>
                    <div class="tab-content pt-4" id="pc-noti-tabContent">
                        <div class="tab-pane fade show active" id="pc-noti-home" role="tabpanel"
                             aria-labelledby="pc-noti-home-tab">
                            <div class="media">
                                <img src="" alt="images" class="img-fluid avtar avtar-l">
                                <div class="media-body ms-3 align-self-center">
                                    <div class="float-end">
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn shadow-none">
                                                <i data-feather="heart" class="text-danger"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn shadow-none px-0 dropdown-toggle arrow-none"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i data-feather="more-horizontal"></i>
                                            </button>
                                            <div class="dropdown dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i data-feather="refresh-cw"></i>
                                                    reload</a>
                                                <a class="dropdown-item" href="#"><i data-feather="trash"></i>
                                                    remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 d-inline-block">Ashoka T.</h6>
                                    <p class="mb-0 d-inline-block f-12 text-muted"> • 06/20/2019 at 6:43 PM </p>
                                    <p class="my-3">Cras sit amet nibh libero in gravida nulla Nulla vel metus
                                        scelerisque ante sollicitudin.</p>
                                    <div class="p-3 border rounded">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <h6 class="mb-1 f-14">Death Star original maps and blueprint.pdf</h6>
                                                <p class="mb-0 text-muted">by<a href="#"> Ashoka T </a>.</p>
                                            </div>
                                            <div class="btn-group d-none d-sm-inline-flex">
                                                <button type="button" class="btn shadow-none">
                                                    <i data-feather="download-cloud"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn shadow-none px-0 dropdown-toggle arrow-none"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#"><i data-feather="refresh-cw"></i>
                                                        reload</a>
                                                    <a class="dropdown-item" href="#"><i data-feather="trash"></i>
                                                        remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-4">
                            <div class="media">
                                <img src="" alt="images" class="img-fluid avtar avtar-l">
                                <div class="media-body ms-3 align-self-center">
                                    <div class="float-end">
                                        <div class="btn-group card-option">
                                            <button type="button"
                                                    class="btn shadow-none px-0 dropdown-toggle arrow-none"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i data-feather="more-horizontal"></i>
                                            </button>
                                            <div class="dropdown dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i data-feather="refresh-cw"></i>
                                                    reload</a>
                                                <a class="dropdown-item" href="#"><i data-feather="trash"></i>
                                                    remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 d-inline-block">Ashoka T.</h6>
                                    <p class="mb-0 d-inline-block  f-12 text-muted"> • 06/20/2019 at 6:43 PM </p>
                                    <p class="my-3">Cras sit amet nibh libero in gravida nulla Nulla vel metus
                                        scelerisque ante sollicitudin.</p>
                                    <img src="" alt="images"
                                         class="img-fluid wid-90 rounded m-r-10 m-b-10">
                                    <img src="" alt="images"
                                         class="img-fluid wid-90 rounded m-r-10 m-b-10">
                                </div>
                            </div>
                            <hr class="mb-4">
                            <div class="media mb-3">
                                <img src="" alt="images" class="img-fluid avtar avtar-l">
                                <div class="media-body ms-3 align-self-center">
                                    <div class="float-end">
                                        3 <i data-feather="heart" class="text-danger fill-danger"></i>
                                    </div>
                                    <h6 class="mb-0 d-inline-block">Ashoka T.</h6>
                                    <p class="mb-0 d-inline-block  f-12 <text-muted></text-muted>"> • 06/20/2019 at 6:43
                                        PM </p>
                                    <p class="my-3">Nulla vitae elit libero, a pharetra augue. Aenean lacinia bibendum
                                        nulla sed consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pc-noti-news" role="tabpanel" aria-labelledby="pc-noti-news-tab">
                            <div class="pb-3 border-bottom mb-3 media">
                                <a href="#"><img src="" class="wid-90 rounded"
                                                 alt="..."></a>
                                <div class="media-body ms-3">
                                    <p class="float-end mb-0 text-success"><small>now</small></p>
                                    <a href="#">
                                        <h6>This is a news image</h6>
                                    </a>
                                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's standard dummy.</p>
                                </div>
                            </div>
                            <div class="pb-3 border-bottom mb-3 media">
                                <a href="#"><img src="" class="wid-90 rounded"
                                                 alt="..."></a>
                                <div class="media-body ms-3">
                                    <p class="float-end mb-0 text-muted"><small>3 mins ago</small></p>
                                    <a href="#">
                                        <h6>Industry's standard dummy</h6>
                                    </a>
                                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting.
                                    </p>
                                    <a href="#" class="bg-light">Html</a>
                                    <a href="#" class="bg-light">UI/UX designed</a>
                                </div>
                            </div>
                            <div class="pb-3 border-bottom mb-3 media">
                                <a href="#"><img src="" class="wid-90 rounded"
                                                 alt="..."></a>
                                <div class="media-body ms-3">
                                    <p class="float-end mb-0 text-muted"><small>5 mins ago</small></p>
                                    <a href="#">
                                        <h6>Ipsum has been the industry's</h6>
                                    </a>
                                    <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting.
                                    </p>
                                    <a href="#" class="bg-light">JavaScript</a>
                                    <a href="#" class="bg-light">Scss</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pc-noti-settings" role="tabpanel"
                             aria-labelledby="pc-noti-settings-tab">
                            <h6 class="mt-2"><i data-feather="monitor" class="me-2"></i>Desktop settings</h6>
                            <hr>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pcsetting1" checked>
                                <label class="custom-control-label f-w-600 pl-1" for="pcsetting1">Allow desktop
                                    notification</label>
                            </div>
                            <p class="text-muted ms-5">you get lettest content at a time when data will updated</p>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pcsetting2">
                                <label class="custom-control-label f-w-600 pl-1" for="pcsetting2">Store Cookie</label>
                            </div>
                            <h6 class="mb-0 mt-5"><i data-feather="save" class="me-2"></i>Application settings</h6>
                            <hr>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pcsetting3">
                                <label class="custom-control-label f-w-600 pl-1" for="pcsetting3">Backup Storage</label>
                            </div>
                            <p class="text-muted mb-4 ms-5">Automaticaly take backup as par schedule</p>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pcsetting4">
                                <label class="custom-control-label f-w-600 pl-1" for="pcsetting4">Allow guest to print
                                    file</label>
                            </div>
                            <h6 class="mb-0 mt-5"><i data-feather="cpu" class="me-2"></i>System settings</h6>
                            <hr>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="pcsetting5" checked>
                                <label class="custom-control-label f-w-600 pl-1" for="pcsetting5">View other user
                                    chat</label>
                            </div>
                            <p class="text-muted ms-5">Allow to show public user message</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-light-primary btn-sm">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pcoded-content">
        <?php if ($viewLayout->ShowExtraMenu): ?>
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">
                                    <i class="material-icons-two-tone">group_work</i>
                                    <span><?= $circle_title ?></span>
                                </h5>
                            </div>
                            <ul id="site-path" class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">Inicio</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- [ Main Content ] start -->
        <div class="row">
            <?php if (!empty($viewSections->main)) require $viewSections->main ?>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->

<!-- Required Js -->
<?= HTML::VendorScripts([
    'popper.min',
    'perfect-scrollbar.min',
    'bootstrap.min',
    'feather.min',
    'pcoded',
    'sweetalert2.all.min'
]); ?>

<!-- OWN JS -->
<?= HTML::Scripts('libs', SubDomains::root) ?>
<?= HTML::Scripts('studio') ?>
<?= $viewLayout->GetAllScripts() ?>
<!-- End OWN JS -->

<div class="pct-customizer">
    <div class="pct-c-btn">
        <button class="btn btn-light-danger" id="pct-toggler">
            <i data-feather="settings"></i>
        </button>
        <button class="btn btn-light-primary" data-bs-toggle="tooltip" title="Document" data-placement="left">
            <i data-feather="book"></i>
        </button>
        <button class="btn btn-light-success" data-bs-toggle="tooltip" title="Buy Now" data-placement="left">
            <i data-feather="shopping-bag"></i>
        </button>
        <button class="btn btn-light-info" data-bs-toggle="tooltip" title="Support" data-placement="left">
            <i data-feather="headphones"></i>
        </button>
    </div>
    <div class="pct-c-content ">
        <div class="pct-header bg-primary">
            <h5 class="mb-0 text-white f-w-500">DashboardKit Customizer</h5>
        </div>
        <div class="pct-body">
            <h6 class="mt-2"><i data-feather="credit-card" class="me-2"></i>Header settings</h6>
            <hr class="my-2">
            <div class="theme-color header-color">
                <a href="#" class="" data-value="bg-default"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-primary"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-danger"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-warning"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-info"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-success"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-dark"><span></span><span></span></a>
            </div>
            <h6 class="mt-4"><i data-feather="layout" class="me-2"></i>Sidebar settings</h6>
            <hr class="my-2">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="cust-sidebar">
                <label class="form-check-label f-w-600 pl-1" for="cust-sidebar">Light Sidebar</label>
            </div>
            <div class="form-check form-switch mt-2">
                <input type="checkbox" class="form-check-input" id="cust-sidebrand">
                <label class="form-check-label f-w-600 pl-1" for="cust-sidebrand">Color Brand</label>
            </div>
            <div class="theme-color brand-color d-none">
                <a href="#" class="active" data-value="bg-primary"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-danger"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-warning"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-info"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-success"><span></span><span></span></a>
                <a href="#" class="" data-value="bg-dark"><span></span><span></span></a>
            </div>
            <h6 class="mt-4"><i data-feather="sun" class="me-2"></i>Layout settings</h6>
            <hr class="my-2">
            <div class="form-check form-switch mt-2">
                <input type="checkbox" class="form-check-input" id="cust-darklayout">
                <label class="form-check-label f-w-600 pl-1" for="cust-darklayout">Dark Layout</label>
            </div>
        </div>
    </div>
</div>

<script>
    feather.replace();
    const pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener('click', function () {
            if (!document.querySelector(".pct-customizer").classList.contains('active')) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var custsidebrand = document.querySelector("#cust-sidebrand");
    custsidebrand.addEventListener('click', function () {
        if (custsidebrand.checked) {
            document.querySelector(".m-header").classList.add("bg-dark");
            document.querySelector(".theme-color.brand-color").classList.remove("d-none");
        } else {
            removeClassByPrefix(document.querySelector(".m-header"), 'bg-');
            // document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute('src', '../assets/images/logo-dark.svg');
            document.querySelector(".theme-color.brand-color").classList.add("d-none");
        }
    });


    var brandcolor = document.querySelectorAll(".brand-color > a");
    for (var t = 0; t < brandcolor.length; t++) {
        var c = brandcolor[t];
        c.addEventListener('click', function (event) {
            var targetElement = event.target;
            if (targetElement.tagName === "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute('data-value');
            if (temp === "bg-default") {
                removeClassByPrefix(document.querySelector(".m-header"), 'bg-');
            } else {
                removeClassByPrefix(document.querySelector(".m-header"), 'bg-');
                document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute('src', '../assets/images/logo.svg');
                document.querySelector(".m-header").classList.add(temp);
            }
        });
    }


    var headercolor = document.querySelectorAll(".header-color > a");
    for (var h = 0; h < headercolor.length; h++) {
        var c = headercolor[h];

        c.addEventListener('click', function (event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute('data-value');
            if (temp == "bg-default") {
                removeClassByPrefix(document.querySelector(".pc-header:not(.pc-mob-header)"), 'bg-');
            } else {
                removeClassByPrefix(document.querySelector(".pc-header:not(.pc-mob-header)"), 'bg-');
                document.querySelector(".pc-header:not(.pc-mob-header)").classList.add(temp);
            }
        });
    }


    var custside = document.querySelector("#cust-sidebar");
    custside.addEventListener('click', function () {
        if (custside.checked) {
            document.querySelector(".pc-sidebar").classList.add("light-sidebar");
            // document.querySelector(".pc-horizontal .topbar").classList.add("light-sidebar");
        } else {
            document.querySelector(".pc-sidebar").classList.remove("light-sidebar");
            // document.querySelector(".pc-horizontal .topbar").classList.remove("light-sidebar");
        }
    });


    var custdarklayout = document.querySelector("#cust-darklayout");
    custdarklayout.addEventListener('click', function () {
        if (custdarklayout.checked) {
            document.querySelector("#main-style-link").setAttribute('href', '../assets/css/style-dark.css');
        } else {
            document.querySelector("#main-style-link").setAttribute('href', '../assets/css/style.css');
        }
    });

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
</script>
</body>
</html>
