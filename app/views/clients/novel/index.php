<?php use HS\libs\helpers\DateUtils;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Url;
use HS\libs\view\View; ?>

<section class="layout-column">
    <section class="right-column">
        <div class="box can-min updates" open>
            <header>
                <div>
                    <h2>
                        <i class="m-icons">history</i>
                        <span>Actualizaciones</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <?php foreach (View::GetClientData()->LastChapters as $chapter): ?>
                        <li>
                            <a class="item"
                               href="/novels/<?= Url::Combine(urlencode($chapter->ProjectName), urlencode($chapter->Name)) ?>">
                                <figure>
                                    <img src="<?= !empty($chapter->ProjectCover) ? UrlFiles::GetProjectIMG($chapter->ProjectName, $chapter->ProjectCover) : '/files/img/basic/novel-cover.png' ?>?h=110"
                                         alt="Portada">
                                </figure>
                                <span class="text">
                                    <span><?= htmlspecialchars($chapter->ProjectTitle) ?></span>
                                    <div class="star-rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </div>
                                    <span class="subtitle <?= $chapter->IsLast ? 'new' : '' ?>">
                                        <?= $chapter->IsLast ? '<i class="m-icons">fiber_new</i>' : '' ?>
                                        <span>Capitulo <?= htmlspecialchars($chapter->Name) ?>: <?= htmlspecialchars($chapter->Title) ?></span>
                                    </span>
                                     <small><?= htmlspecialchars(DateUtils::GetNaturalDate($chapter->CreatedDate, true)) ?></small>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <footer class="no-padding">
                <a href="#" class="btn-text">
                    <span>Ver mas...</span>
                </a>
            </footer>
        </div>

        <div class="box can-min new-novels" open>
            <header>
                <div>
                    <h2>
                        <i class="m-icons">new_releases</i>
                        <span>Nuevas</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list mosaic">
                    <?php foreach (View::GetClientData()->LastNovels as $novel): ?>
                        <li>
                            <a class="item" href="/novels/<?= htmlspecialchars(urlencode($novel->Name)) ?>">
                                <figure>
                                    <img src="<?= $novel->GetCoverUrl() ?>?w=207"
                                         alt="Portada">
                                </figure>
                                <span class="text">
                                    <span><?= htmlspecialchars($novel->Title) ?></span>
                                    <span class="subtitle rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </span>
                                    <span>
                                        <i class="m-icons">calendar_today</i>
                                        <?= htmlspecialchars(DateUtils::GetNaturalDate($novel->CreatedAt)) ?>
                                    </span>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <aside class="left-column navbar">
        <div class="box">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">auto_stories</i>
                        <span>Novelas populares</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <?php foreach (View::GetClientData()->MostPopularNovels as $index => $novel): ?>
                        <li>
                            <a class="item"
                               href="/novels/<?= urlencode($novel->Name) ?>">
                                <figure>
                                    <img src="<?= $novel->GetCoverUrl() ?>?h=80"
                                         alt="Portada">
                                </figure>
                                <div class="text">
                                    <span><?= $index + 1 . '. ' . htmlspecialchars($novel->Title) ?></span>
                                    <div class="star-rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </div>
                                    <small>
                                        <i class="m-icons">auto_stories</i>
                                        <span>
                                            <?= $novel->ChapterCount . " capítulo" . ($novel->ChapterCount == 1 ? '' : 's') ?>
                                        </span>
                                    </small>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="box">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">auto_stories</i>
                        <span>Novelas mejor votadas</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <?php foreach (View::GetClientData()->MostPopularNovels as $index => $novel): ?>
                        <li>
                            <a class="item"
                               href="/novels/<?= urlencode($novel->Name) ?>">
                                <figure>
                                    <img src="<?= $novel->GetCoverUrl() ?>?h=80"
                                         alt="Portada">
                                </figure>
                                <div class="text">
                                    <span><?= $index + 1 . '. ' . htmlspecialchars($novel->Title) ?></span>
                                    <div class="star-rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </div>
                                    <small>
                                        <i class="m-icons">auto_stories</i>
                                        <span>
                                            <?= $novel->ChapterCount . " capítulo" . ($novel->ChapterCount == 1 ? '' : 's') ?>
                                        </span>
                                    </small>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="box">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">hub</i>
                        <span>Círculos populares</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <ul class="list">
                    <li>
                        <a class="item" href="#6">
                            <figure>
                                <img src="/files/img/basic/Logo.png" alt="Logo">
                            </figure>
                            <span class="text">
                                    <span>HunterStars</span>
                                    <div class="subtitle rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </div>
                                    <span>
                                        <i class="m-icons">volunteer_activism</i> 3 obras
                                    </span>
                                </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</section>