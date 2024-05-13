<?php use HS\app\models\client\items\NovelStateItem;
use HS\libs\view\View;

$states = View::GetClientData()->NovelStateCount;
$active = $states[NovelStateItem::ACTIVE - 1];
$paused = $states[NovelStateItem::PAUSED - 1];
$drop = $states[NovelStateItem::DROP - 1];
$finished = $states[NovelStateItem::FINISHED - 1];

function work_count($count): string
{
    return $count . " " . ($count == 1 ? 'obra' : 'obras');
}

?>

<section id="header-content">
    <div class="box">
        <div class="content no-padding">
            <nav class="tabs">
                <div class="tabs-header">
                    <button class="btn tab" data-tabname="tab-online" pressed="">
                        <i class="m-icons">import_contacts</i>
                        <span>Novelas</span>
                    </button>
                </div>
                <div class="tabs-content">
                    <div class="tools-box mosaic">
                        <a class="btn Admin" href="#">
                            <i class="m-icons"><?= htmlspecialchars($active->Icon) ?></i>
                            <span class="text">
                                    <span>Activas</span>
                                    <span><?= work_count($active->Count); ?></span>
                                </span>
                        </a>
                        <a class="btn" href="#">
                            <i class="m-icons"><?= htmlspecialchars($paused->Icon) ?></i>
                            <span class="text">
                                    <span>Pausadas</span>
                                    <span><?= work_count($paused->Count); ?></span>
                                </span>
                        </a>
                        <a class="btn" href="#">
                            <i class="m-icons"><?= htmlspecialchars($drop->Icon) ?></i>
                            <span class="text">
                                    <span>Abandonadas</span>
                                    <span><?= work_count($drop->Count); ?></span>
                                </span>
                        </a>
                        <a class="btn" href="#">
                            <i class="m-icons"><?= htmlspecialchars($finished->Icon) ?></i>
                            <span class="text">
                                    <span>Finalizadas</span>
                                    <span><?= work_count($finished->Count); ?></span>
                                </span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</section>