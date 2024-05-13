<?php

use HS\libs\io\Url;
use HS\libs\view\View;

$data = View::GetClientData();
$chapter = $data->Chapter;
$circle = $data->Circle;
?>
<section class="layout-column">
    <section class="right-column">
        <section id="novel-reader" class="box">
            <header>
                <div>
                    <h2 style="text-align: center">
                        Lector de capítulos
                    </h2>
                </div>
            </header>
            <div class="content">
                <div class="tools-box">
                    <a class="btn" href="<?= urlencode($chapter->BackName ?? '') ?>"
                       title="Capítulo anterior: <?= htmlspecialchars($chapter->BackTitle ?? '') ?>"
                        <?= empty($chapter->BackName) ? 'disabled' : '' ?>>
                        <i class="m-icons">arrow_back_ios</i>
                        <span>Anterior</span>
                    </a>
                    <button class="btn-icon btn-font-minus" title="Disminuir tamaño del texto">
                        <i class="m-icons">text_decrease</i>
                    </button>
                    <a class="btn-icon" href="." title="Lista de capítulos">
                        <i class="m-icons">view_list</i>
                    </a>
                    <button class="btn-icon btn-font-plus" title="Aumentar tamaño del texto">
                        <i class="m-icons">text_increase</i>
                    </button>
                    <a class="btn" href="<?= urlencode($chapter->NextName ?? '') ?>"
                       title="Capítulo siguiente: <?= htmlspecialchars($chapter->NextTitle ?? '') ?>"
                        <?= empty($chapter->NextName) ? 'disabled' : '' ?>>
                        <span>Siguiente</span>
                        <i class="m-icons">arrow_forward_ios</i>
                    </a>
                </div>
                <div class="ck-content">
                    <h1>Capítulo <?= htmlspecialchars($chapter->Name) ?>: <?= htmlspecialchars($chapter->Title) ?></h1>
                    <?= $chapter->Content ?>
                </div>
            </div>
            <footer>
                <div class="tools-box">
                    <a class="btn" href="<?= urlencode($chapter->BackName ?? '') ?>"
                       title="Capítulo anterior: <?= htmlspecialchars($chapter->BackTitle ?? '') ?>"
                        <?= empty($chapter->BackName) ? 'disabled' : '' ?>>
                        <i class="m-icons">arrow_back_ios</i>
                        <span>Anterior</span>
                    </a>
                    <a class="btn" href="." title="Lista de capítulos">
                        <i class="m-icons">view_list</i>
                        <span>Lista</span>
                    </a>
                    <a class="btn" href="<?= urlencode($chapter->NextName ?? '') ?>"
                       title="Capítulo siguiente: <?= htmlspecialchars($chapter->NextTitle ?? '') ?>"
                        <?= empty($chapter->NextName) ? 'disabled' : '' ?>>
                        <span>Siguiente</span>
                        <i class="m-icons">arrow_forward_ios</i>
                    </a>
                </div>
            </footer>
        </section>

        <section class="box comments">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">chat</i>
                        <span>Comentarios</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <?php require 'sections/comments.php' ?>
            </div>
        </section>
    </section>

    <aside class="left-column navbar">
        <div class="box can-min donate">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">local_cafe</i>
                        <span>Donar taza de cafe</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <span class="subtitle-2">Seleccione un método de donación:</span>
                <ul class="list">
                    <li>
                        <a class="item paypal-method" href="#6">
                            <figure>
                                <img src="/files/img/icon/paypal.svg" alt="Paypal">
                            </figure>
                            <span class="text">
                                    <span>Paypal</span>
                                    <span>Donación libre ($)</span>
                                </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="box provider">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">hub</i>
                        <span>Círculo proveedor</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <li>
                        <a class="item" href="#<?= htmlspecialchars(urlencode($circle->Name)) ?>">
                            <figure>
                                <img src="/files/img/basic/Logo.png" alt="Logo">
                            </figure>
                            <span class="text">
                                    <span><?= htmlspecialchars($circle->Title) ?></span>
                                    <div class="subtitle rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </div>
                                    <span>
                                        <i class="m-icons">volunteer_activism</i>
                                        <?= htmlspecialchars($circle->ProjectCount) . " obra" . ($circle->ProjectCount == 1 ? '' : 's') ?>
                                    </span>
                                </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!--<div class="box team">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">people</i>
                        <span>Participantes</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <li>
                        <a class="item" href="#6">
                            <figure>
                                <img src="/files/img/upload/profile/Errdex.png" alt="Perfil">
                            </figure>
                            <span class="text">
                                    <span>Errdex HS</span>
                                    <span>Traductor</span>
                                </span>
                        </a>
                    </li>
                    <li>
                        <a class="item" href="#6">
                            <figure>
                                <img src="/files/img/basic/Logo.png" alt="Perfil">
                            </figure>
                            <span class="text">
                                    <span>Eliezar Hernández</span>
                                    <span>Corrector</span>
                                </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>-->
    </aside>
</section>