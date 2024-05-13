<?php

use HS\libs\core\Session;

$user = Session::GetOnlyRead()->User;
?>

<div class="row">
    <div class="left-column col-sm-12 col-lg-4">
        <div class="box">
            <header>
                <picture>
                    <img src="/files/img/basic/Logo.png" alt="Perfil">
                </picture>
                <div class="ms-3">
                    <h2 class="account-user-shortname"><?= htmlspecialchars($user->GetShortName()) ?></h2>
                    <h6 class="subtitle-2">Usuario normal</h6>
                </div>
            </header>
            <div class="content p-0">
                <div class="tools-box mosaic">
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">chat</i>
                        <span class="text">
                                    <span>Mensajes</span>
                                </span>
                    </a>
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">account_box</i>
                        <span class="text">
                                    <span>Contactos</span>
                                </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="box mt-4">
            <div class="content p-0">
                <div class="tools-box mosaic">
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">hub</i>
                        <span class="text">
                                    <span>?</span>
                                    <span>Círculos</span>
                                </span>
                    </a>
                    <a class="btn" href="/user/favorites">
                        <i class="m-icons">loyalty</i>
                        <span class="text">
                                    <span>?</span>
                                    <span>Favoritos</span>
                                </span>
                    </a>
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">rss_feed</i>
                        <span class="text">
                                    <span>?</span>
                                    <span>Seguidos</span>
                                </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="box mt-4">
            <div class="content p-0">
                <ul class="list tabs-header m-0">
                    <li>
                        <div class="item tab" data-tabname="tab-account-info" pressed>
                            <i class="m-icons">account_circle</i>
                            <span class="text">Información de la cuenta</span>
                            <i class="m-icons">navigate_next</i>
                        </div>
                    </li>
                    <li>
                        <div class="item tab" data-tabname="tab-password">
                            <i class="m-icons">key</i>
                            <span class="text">Cambiar contraseña</span>
                            <i class="m-icons">navigate_next</i>
                        </div>
                    </li>
                    <li>
                        <div class="item tab" disabled>
                            <i class="m-icons">settings</i>
                            <span class="text">Ajustes</span>
                            <i class="m-icons">navigate_next</i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="right-column col-sm-12 col-lg-8 mt-4 mt-lg-0">
        <div class="tabs-content">
            <div class="tab-item" data-tabname="tab-account-info" show>
                <form id="form-personal-information" class="flex-grow-1">
                    <div class="box flex-grow-1">
                        <header>
                            <div>
                                <h2>
                                    <i class="m-icons">account_circle</i>
                                    <span>Información de la cuenta</span>
                                </h2>
                                <h3 class="ms-4 ps-3">
                                    Cambia la configuración de tu cuenta
                                </h3>
                            </div>
                        </header>
                        <div class="content">
                            <div class="form-info">
                            </div>
                            <div class="p-3 pt-2">
                                <h6 class="m-0 mb-3">General</h6>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="user-firstname">Nombres <span>*</span></label>
                                        <div class="input-text">
                                            <input type="text" id="user-firstname" name="fname"
                                                   autocomplete="given-name" required
                                                   value="<?= htmlspecialchars($user->FirstName) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label for="user-lastname">Apellidos <span>*</span></label>
                                        <div class="input-text">
                                            <input type="text" id="user-lastname" name="lname"
                                                   autocomplete="family-name" required
                                                   value="<?= htmlspecialchars($user->LastName) ?>">
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-4">

                                <h6 class="mt-3 mb-3">Cuenta</h6>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="user-name">Nombre de usuario <span>*</span></label>
                                        <div class="input-text">
                                            <input type="text" id="user-name" name="uname" autocomplete="username"
                                                   minlength="3" maxlength="12" required
                                                   value="<?= htmlspecialchars($user->Nick) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label for="user-email">Correo electrónico</label>
                                        <div class="input-text">
                                            <input type="email" id="user-email" name="email" autocomplete="email"
                                                   placeholder="correo@ejemplo.com"
                                                   value="<?= htmlspecialchars($user->Email) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="p-3">
                            <div class="col"></div>
                            <div class="col-auto">
                                <input type="submit" class="btn-fill p-2 ps-3 pe-3" value="Cambiar"/>
                                <input type="reset" class="btn-outline p-2 ps-3 pe-3" value="Limpiar"/>
                            </div>
                        </footer>
                    </div>
                </form>
            </div>
            <div class="tab-item" data-tabname="tab-password">
                <form id="form-change-password" class="flex-grow-1">
                    <div class="box flex-grow-1">
                        <header>
                            <div>
                                <h2>
                                    <i class="m-icons">lock</i>
                                    <span>Cambiar contraseña</span>
                                </h2>
                            </div>
                        </header>
                        <div class="content">
                            <div class="form-info">
                            </div>
                            <div class="p-3 pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="user-pass">Contraseña actual <span>*</span></label>
                                        <div class="input-text">
                                            <input type="password" id="user-pass" name="pass"
                                                   autocomplete="current-password"
                                                   minlength="8" maxlength="50" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="user-new-pass">Nueva contraseña <span>*</span></label>
                                        <div class="input-text">
                                            <input type="password" id="user-new-pass" name="new-pass"
                                                   autocomplete="new-password"
                                                   minlength="8" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mt-4 mt-md-0">
                                        <label for="user-pass-confirm">Confirmar contraseña <span>*</span></label>
                                        <div class="input-text">
                                            <input type="password" id="user-pass-confirm" name="re-pass"
                                                   autocomplete="off"
                                                   minlength="8" maxlength="50" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="p-3">
                            <div class="col"></div>
                            <div class="col-auto">
                                <input type="submit" class="btn-fill p-2 ps-3 pe-3" value="Cambiar"/>
                                <input type="reset" class="btn-outline p-2 ps-3 pe-3" value="Limpiar"/>
                            </div>
                        </footer>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>