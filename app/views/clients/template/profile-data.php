<?php

use HS\config\enums\SubDomains;
use HS\libs\core\Session;

$session = Session::Get();
?>

<div class="profile-data">
    <?php if ($session->IsLogin()): ?>
        <div class="user-info">
            <picture>
                <img src="/files/img/basic/Logo.png" alt="Foto">
            </picture>
            <div>
                <h5 class="account-user-shortname"><?= htmlspecialchars($session->User->GetShortName()) ?></h5>
                <h5>
                    <i class="m-icons">star</i>
                    <i class="m-icons">star</i>
                    <i class="m-icons">star</i>
                    <i class="m-icons">star_half</i>
                    <i class="m-icons">star_border</i>
                </h5>
                <h6 class="subtitle-2">Usuario Normal</h6>
            </div>
        </div>
        <div class="user-action">
            <a class="btn-fill-shadow mini" href="https://<?= SubDomains::studio->value ?>">
                <i class="m-icons">work</i>
                <span>Panel de Administración</span>
            </a>
        </div>
    <?php else: ?>
        <div class="user-action">
            <a href="/register" class="btn-outline mini register" style="font-weight: bold;">
                <i class="m-icons">exit_to_app</i>
                <span>Registrarse</span>
            </a>
            <a href="/login" class="btn-fill-shadow mini">
                <i class="m-icons">exit_to_app</i>
                <span>Iniciar sesión</span>
            </a>
        </div>
    <?php endif; ?>
</div>