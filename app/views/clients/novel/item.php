<?php

use HS\app\models\client\items\ChapterItem;
use HS\app\models\client\items\GroupItem;
use HS\app\models\client\items\NovelItem;
use HS\app\models\items\CommentItem;
use HS\libs\helpers\DataUtils;
use HS\libs\helpers\DateUtils;
use HS\libs\io\Url;
use HS\libs\view\View;

$data = View::GetClientData();
$novel = $data->Novel;
$circle = $data->Circle;
?>

    <section class="top-data">
        <section class="box" open>
            <div class="content">
                <figure>
                    <img src="<?= $novel->GetCoverUrl() ?>" alt="">
                    <figcaption class="<?= htmlspecialchars($novel->StateItem->Class) ?>">
                        <div>
                            <i class="m-icons"><?= htmlspecialchars($novel->StateItem->Icon) ?></i>
                            <span><?= htmlspecialchars($novel->StateItem->Name) ?></span>
                        </div>
                    </figcaption>
                </figure>
                <div class="info">
                    <div class="scrollbar">
                        <h4><?= htmlspecialchars($novel->Title) ?></h4>
                        <h5 class="subtitle-2"><?= htmlspecialchars($novel->TitleAlt) ?></h5>
                        <div class="rating">
                            <i class="m-icons">star</i>
                            <i class="m-icons">star</i>
                            <i class="m-icons">star</i>
                            <i class="m-icons">star_half</i>
                            <i class="m-icons">star_border</i>
                        </div>
                        <nav>
                            <?php foreach ($novel->Categories as $tag): ?>
                                <a href="#" class="chip">
                                    <i class="m-icons">local_offer</i>
                                    <span><?= htmlspecialchars($tag->Name) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <div class="synopsis scrollbar">
                        <?= $novel->Synopsis ?>
                    </div>

                </div>
            </div>
            <footer>
                <button id="btn-favorite" class="btn-text">
                    <?php if ($novel->IsFavorite): ?>
                        <i class="m-icons">favorite</i>
                        <span>Favorito</span>
                    <?php else: ?>
                        <i class="m-icons">favorite_border</i>
                        <span>Agregar a favoritos</span>
                    <?php endif; ?>
                </button>
                <a href="#" class="btn-text" disabled="">
                    <i class="m-icons">today</i>
                    <span>Seguir novela</span>
                </a>
                <a href="#" class="btn-text" disabled="">
                    <i class="m-icons">star_half</i>
                    <span>Votar</span>
                </a>
            </footer>
        </section>
    </section>

    <section class="bottom-data layout-column">
        <section class="right-column">
            <section class="box vol-cap-list">
                <header>
                    <div>
                        <h2>
                            <i class="m-icons">view_list</i>
                            <span>Volúmenes y capítulos</span>
                        </h2>
                    </div>
                </header>
                <div class="content">
                    <div class="tabs">
                        <div class="tabs-header">
                            <button class="btn tab" data-tabname="tab-online" pressed="">
                                <i class="m-icons">public</i>
                                <span>Online</span>
                            </button>
                            <button class="btn tab" data-tabname="tab-pdf">
                                <i class="m-icons">picture_as_pdf</i>
                                <span>Pdf</span>
                            </button>
                            <!--<button class="btn tab">
                                <i class="m-icons">cloud_download</i>
                                <span>Web offline</span>
                            </button>-->
                        </div>
                        <div class="tabs-content">
                            <div class="tab-item" data-tabname="tab-online" show="">
                                <ul class="list">
                                    <?php foreach ($novel->Entries as $key => $chapter):
                                        if (is_int($key))
                                            chapter_item_html($novel->Name, $chapter);
                                        else
                                            group_item_html($novel->Name, $novel->Groups[$key], $chapter, false);
                                    endforeach; ?>
                                </ul>
                            </div>
                            <div class="tab-item" data-tabname="tab-pdf">
                                <?php if (empty($novel->Groups)): ?>
                                    <a class="btn btn-outline"
                                       href="<?= htmlspecialchars(Url::Combine($_SERVER['REQUEST_URI'], 'download')) ?>"
                                       title="Descargar PDF" style="width: 100%">
                                        <i class="m-icons">download</i>
                                        <span>¡Descargar todo como PDF!</span>
                                    </a>
                                <?php else: ?>
                                    <ul class="list">
                                        <?php foreach ($novel->Entries as $key => $chapter):
                                            if (!is_int($key))
                                                group_item_html($novel->Name, $novel->Groups[$key], $chapter, true);
                                        endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <span class="subtitle rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </span>
                                    <span>
                                        <i class="m-icons">volunteer_activism</i>
                                        <?= $circle->ProjectCount . " obra" . ($circle->ProjectCount == 1 ? '' : 's') ?>
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

<?php
function chapter_item_html(string $project_name, ChapterItem $chapter): void
{ ?>
    <li>
        <article class="item">
            <a href="/novels/<?= urlencode($project_name) . '/' . urlencode($chapter->Name) ?>">
                <span class="text">
                    <span>
                        Capítulo <?= htmlspecialchars($chapter->Name) ?>: <?= htmlspecialchars($chapter->Title) ?>
                    </span>
                </span>
                <span class="subtitle-2">
                    <?= htmlspecialchars(DateUtils::GetNaturalDate($chapter->CreatedDate)) ?>
                </span>
            </a>
            <button class="btn-icon m-icons">share</button>
        </article>
    </li>
<?php }

function group_item_html(string $project_name, GroupItem $group, array $chapters, bool $export): void
{ ?>
    <li>
        <article class="item">
            <figure>
                <img src="<?= $group->GetCoverUrl($project_name) ?>"
                     alt="Portada">
            </figure>
            <span class="text">
                <span><?= htmlspecialchars($group->Title) ?></span>
                <span>
                    <i class="m-icons">auto_stories</i>
                    <span>
                        <?= count($chapters) . " capítulo" . (count($chapters) == 1 ? '' : 's') ?>
                    </span>
                </span>
            </span>
            <?php if ($export): ?>
                <a class="btn-icon m-icons" style="color: green"
                   href="<?= htmlspecialchars(Url::Combine($_SERVER['REQUEST_URI'], 'download', "$group->ID.pdf")) ?>"
                   title="Descargar PDF"> downloading
                </a>
            <?php endif; ?>
        </article>

        <?php if (!$export): ?>
            <ul class="list">
                <?php array_map(fn($item) => chapter_item_html($project_name, $item), $chapters) ?>
            </ul>
        <?php endif; ?>
    </li>
<?php }


