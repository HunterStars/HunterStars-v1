<?php

use HS\libs\helpers\DateUtils;
use HS\libs\helpers\UrlMaker;
use HS\libs\view\View;

$data = View::GetClientData();
$favorites = $data->Favorites;
?>

<div class="row mb-5">
    <div class="col-12">
        <div class="box">
            <div class="content no-padding">
                <div class="tools-box mosaic">
                    <a class="btn Admin" href="#" disabled>
                        <i class="m-icons">local_movies</i>
                        <span class="text">
                                    <span>Animes</span>
                                    <span>0</span>
                                </span>
                    </a>
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">insert_photo</i>
                        <span class="text">
                                    <span>Mangas</span>
                                    <span>0</span>
                                </span>
                    </a>
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">headset</i>
                        <span class="text">
                                    <span>Musica</span>
                                    <span>0</span>
                                </span>
                    </a>
                    <a class="btn focus" href="#">
                        <i class="m-icons">import_contacts</i>
                        <span class="text">
                                    <span>Novelas</span>
                                    <span>1</span>
                                </span>
                    </a>
                    <a class="btn" href="#" disabled>
                        <i class="m-icons">games</i>
                        <span class="text">
                                    <span>Juegos</span>
                                    <span>0</span>
                                </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (count($favorites) == 0): ?>
    <div class="box">
        <div class="content" style="text-align: center">
            <h4>¡No tienes <!--<b>novelas</b> favoritas--> favoritos en tu biblioteca!</h4>
            <h5 class="subtitle">Busca <!--algunas que te gusten y añádelas.--> algo que te guste y añádelo</h5>
        </div>
    </div>
<?php else: ?>
    <div class="row list mosaic">
        <?php foreach ($favorites as $project): ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a class="item"
                   href="<?= htmlspecialchars(UrlMaker::GetProject($project->TypeName, $project->Name)) ?>">
                    <figure>
                        <img src="<?= htmlspecialchars($project->GetClientCoverUrl()) ?>?w=207"
                             alt="Portada">
                    </figure>
                    <span class="text">
                                    <span><?= htmlspecialchars($project->Title) ?></span>
                                    <span class="subtitle rate">
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star</i>
                                        <i class="m-icons">star_half</i>
                                        <i class="m-icons">star_border</i>
                                    </span>
                                    <span>
                                        <i class="m-icons">calendar_today</i>
                                        <?= htmlspecialchars(DateUtils::GetNaturalDate($project->CreatedAt)) ?>
                                    </span>
                                </span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!--<div class="row mt-4">
    <div class="col-lg-4 col-sm-6">
        <div class="thumbnail mb-4">
            <div class="thumb">
                <a href="../assets/images/gallery-grid/img-grd-gal-1.jpg" data-lightbox="1" data-title="My caption 1">
                    <img src="../assets/images/gallery-grid/img-grd-gal-1.jpg" alt="" class="img-fluid img-thumbnail">
                </a>
            </div>
        </div>
    </div>
</div>-->


