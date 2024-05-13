<?php

use HS\config\enums\SubDomains;
use HS\libs\core\Session;
use HS\libs\helpers\HTML;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Path;
use HS\libs\view\View;
use const HS\APP_DEBUG;

$session = Session::Get();
$isLogin = $session->IsLogin();

$viewLayout = View::GetLayout();
?>

<!doctype html>
<html lang="es">
<head>
    <?php if (!APP_DEBUG): ?>
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-58ZBGR7');</script>
        <!-- End Google Tag Manager -->
    <?php endif; ?>

    <title><?= empty($viewLayout->Title) ? '' : "$viewLayout->Title | " ?>HunterStars</title>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description"
          content="HunterStars, ¡un sitio donde encuentras de todo! Musica, Anime, Juegos, Novelas, Mangas."/>
    <meta name="keywords" content="HunterStars Novelas Anime Juegos Mangas Musica Japón"/>
    <meta name="author" content="HunterStars"/>

    <link rel="icon" href="/files/icon/favicon.ico" type="image/x-icon">

    <!--Styles-->
    <?= HTML::Styles('basic'); ?>
    <?= HTML::Styles('bootstrap-grid.min') ?>
    <link rel="stylesheet" href="<?= UrlFiles::GetFile('material-icons/material-icons.css', SubDomains::root) ?>">
    <?= $viewLayout->GetStyles() ?>
    <!--End Styles -->
</head>
<body>
<?php if (!APP_DEBUG): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58ZBGR7"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<header>
    <section id="top-menu">
        <button class="btn-icon menu">
            <i class="m-icons">menu</i>
            <i class="m-icons">close</i>
        </button>
        <div class="text-logo">
            <a href="/"><img src="/files/img/basic/hs-text.png" alt="HunterStars"></a>
        </div>
        <button class="btn-icon m-icons search">search</button>
        <?php if ($isLogin): ?>
            <button class=" btn-icon m-icons notification">notifications</button>
        <?php endif; ?>
        <button class="btn-icon m-icons account">account_circle</button>
    </section>

    <?php if ($viewLayout->ShowExtraMenu): ?>
        <section id="extra-menu">
            <form class="search-box">
                <div class="input-text">
                    <label class="m-icons" for="search">search</label>
                    <input type="search" id="search" placeholder="Ingrese un termino de búsqueda">
                </div>
            </form>
            <div class="account-box">
                <?php require Path::GetViewClient('/template/profile-data') ?>

                <div class="tools-box mosaic">
                    <?php if ($isLogin): ?>
                        <a class="btn admin" href="https://<?= SubDomains::studio->value ?>">
                            <i class="m-icons">work</i>
                            <span>Panel de Administración</span>
                        </a>
                        <button class="btn" disabled>
                            <i class="m-icons">group</i>
                            <span>Mis grupos</span>
                        </button>
                        <a class="btn" href="/user/favorites">
                            <i class="m-icons">loyalty</i>
                            <span>Mis favoritos</span>
                        </a>
                        <a class="btn" href="/user/settings">
                            <i class="m-icons">settings</i>
                            <span>Ajustes</span>
                        </a>
                        <a class="btn" href="/logout">
                            <i class="m-icons">power_settings_new</i>
                            <span>Cerrar sesión</span>
                        </a>
                    <?php else: ?>
                        <a class="btn" href="/register">
                            <i class="m-icons">exit_to_app</i>
                            <span>Registrarse</span>
                        </a>
                        <a class="btn" href="/login">
                            <i class="m-icons">vpn_key</i>
                            <span>Iniciar sesión</span>
                        </a>
                    <?php endif; ?>

                    <button class="btn-icon night" disabled>
                        <i class="m-icons">brightness_2</i>
                        <i class="m-icons">brightness_4</i>
                        <span>Modo Noche</span>
                    </button>
                </div>
            </div>

            <?php if ($isLogin): ?>
                <div class="notification-box">
                    <div class="tabs">
                        <div class="tabs-header border-bottom">
                            <button class="btn tab" data-tabname="tab-notification" pressed>
                                <i class="m-icons">notifications</i>
                                <span>Notificaciones</span>
                            </button>
                            <a href="#" class="btn tab" data-tabname="tab-msg">
                                <i class="m-icons">forum</i>
                                <span>Mensajes</span>
                            </a>
                        </div>
                        <div class="tabs-content">
                            <div class="tab-item" data-tabname="tab-notification" show>

                            </div>
                            <nav class="tab-item" data-tabname="tab-msg">

                            </nav>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if ($viewLayout->ShowLateralMenu): ?>
        <section id="lateral-menu">
            <div class="top-submenu">
                <?php require Path::GetViewClient('/template/profile-data') ?>

                <nav class="site-map scrollbar no-hover">
                    <?php if ($isLogin) : ?>
                        <ul class="list user-action">
                            <li>
                                <a class="item" href="" disabled>
                                    <i class="m-icons">group</i>
                                    <span class="text">
                                Mis grupos
                            </span>
                                </a>
                            </li>
                            <li>
                                <a class="item" href="/user/favorites">
                                    <i class="m-icons">loyalty</i>
                                    <span class="text">Mis favoritos</span>
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <ul class="list nav-links">
                        <?php if ($isLogin) : ?>
                            <li class="subtitle">
                                Sitios
                            </li>
                        <?php endif; ?>
                        <li class="home">
                            <a class="item" href="/">
                                <i class="m-icons">home</i>
                                <span class="text">Inicio</span>
                            </a>
                        </li>
                        <?php if (!$isLogin) : ?>
                            <li class="subtitle">
                                Sitios
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">local_movies</i>
                                <span class="text">
                            Anime
                        </span>
                            </a>
                            <ul>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">label_outline</i>
                                        <span class="text">
                                        <span>Todos</span>
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">label_outline</i>
                                        <span class="text">
                                        <span>En emisión</span>
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">label_outline</i>
                                        <span class="text">
                                        <span>Finalizados</span>
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">insert_photo</i>
                                <span class="text">Mangas</span>
                            </a>
                        </li>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">headset</i>
                                <span class="text">Musica</span>
                            </a>
                        </li>
                        <li>
                            <a class="item" href="/novels/">
                                <i class="m-icons">import_contacts</i>
                                <span class="text">Novelas</span>
                            </a>
                            <ul>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">play_circle_outline</i>
                                        <span class="text">Activas</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">pause_circle_outline</i>
                                        <span class="text">Pausadas</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">label_outline</i>
                                        <span class="text">Abandonadas</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="item" href="#">
                                        <i class="m-icons">label_outline</i>
                                        <span class="text">Terminadas</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">games</i>
                                <span class="text">Juegos</span>
                            </a>
                        </li>
                        <li class="subtitle" disabled>
                            Ayuda y contacto
                        </li>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">info_outline</i>
                                <span class="text">
                                Contacto
                            </span>
                            </a>
                        </li>
                        <li>
                            <a class="item" href="#" disabled>
                                <i class="m-icons">help_outline</i>
                                <span class="text">
                                Ayuda
                            </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="tools-box mosaic">
                <?php if ($isLogin) : ?>
                    <a class="btn" href="/logout">
                        <i class="m-icons">power_settings_new</i>
                        <span>Cerrar sesión</span>
                    </a>
                    <a class="btn" href="/user/settings">
                        <i class="m-icons">settings</i>
                        <span>Ajustes</span>
                    </a>
                    <button class="btn" disabled>
                        <i class="m-icons">forum</i>
                        <span>Msg y Notificaciones</span>
                    </button>
                <?php endif; ?>

                <button class="btn-icon Noche" disabled>
                    <i class="m-icons">brightness_2</i>
                    <i class="m-icons">brightness_4</i>
                    <span>Modo Noche</span>
                </button>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($viewLayout->Sections->header)) require $viewLayout->Sections->header ?>
</header>

<main role="main">
    <?php if (!empty($viewLayout->Sections->main)) require $viewLayout->Sections->main ?>
</main>

<!--Scripts-->
<?= HTML::Scripts(['libs', 'items'], SubDomains::root) ?>
<?= $viewLayout->GetAllScripts() ?>
<!--End Scripts-->
</body>
</html>