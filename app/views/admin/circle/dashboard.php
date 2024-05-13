<?php

use HS\app\models\client\items\NovelItem;
use HS\app\models\items\EntryItem;
use HS\app\models\items\ProjectItem;
use HS\libs\io\Url;
use HS\libs\view\View;

$data = View::GetData();
$dashboard = $data->Dashboard;
?>

<div class="col-sm-12">
    <div class="card flat-card">
        <div class="row-table">
            <div class="col-sm-3 card-body br">
                <div class="row">
                    <div class="col-auto ms-auto ps-0">
                        <i class="material-icons-two-tone text-primary mb-1">description</i>
                    </div>
                    <div class="col-auto me-auto text-md-center">
                        <h5><?= htmlspecialchars($dashboard->EntriesCount) ?></h5>
                        <span>Capítulos</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 card-body br">
                <div class="row">
                    <div class="col-auto ms-auto ps-0">
                        <i class="material-icons-two-tone text-primary mb-1">book</i>
                    </div>
                    <div class="col-auto me-auto text-md-center">
                        <h5><?= htmlspecialchars($dashboard->ProjectsCount) ?></h5>
                        <span>Novelas</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 card-body">
                <div class="row">
                    <div class="col-auto ms-auto ps-0">
                        <i class="material-icons-two-tone text-primary mb-1">people</i>
                    </div>
                    <div class="col-auto me-auto text-md-center">
                        <h5><?= htmlspecialchars($dashboard->MembersCount) ?></h5>
                        <span>Miembros</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6 col-md-6 col-sm-12">
    <div class="card analytic-card">
        <div class="card-body">
            <div class="row align-items-center m-b-25">
                <div class="col-auto">
                    <i class="fas fa-eye text-primary f-18 analytic-icon"></i>
                </div>
                <div class="col text-end">
                    <h3 class="m-b-5 text-white"><?= htmlspecialchars($dashboard->TotalViews ?? 0) ?></h3>
                    <h6 class="m-b-0 text-white">Vistas totales</h6>
                </div>
            </div>
            <p class="m-b-0  text-white d-inline-block">Vistas de hoy : </p>
            <h5 class=" text-white d-inline-block m-b-0 m-l-10"><?= htmlspecialchars($dashboard->ViewsToday ?? 0) ?></h5>
            <h6 class="m-b-0 d-inline-block  text-white float-end">

                <?php
                if ($dashboard->ViewsToday === $dashboard->ViewsYesterday)
                    $perc = 0;
                else if ($dashboard->ViewsYesterday == 0)
                    $perc = ((($dashboard->ViewsToday ?? 0) / ($dashboard->ViewsYesterday ?? 1))) * 100;
                else
                    $perc = ((($dashboard->ViewsToday ?? 0) / ($dashboard->ViewsYesterday ?? 1)) - 1) * 100;

                if ($perc > 0): ?>
                    <span class="font-extrabold text-success">
                        <i class="feather icon-arrow-up f-18"></i><?= htmlspecialchars(number_format($perc, 2, '.')) ?>%
                    </span> vs ayer
                <?php elseif ($perc < 0): ?>
                    <span class="font-extrabold text-danger">
                         <i class="feather icon-arrow-down f-18"></i><?= htmlspecialchars(number_format(abs($perc), 2, '.')) ?>%
                    </span> vs ayer
                <?php else: ?>
                    Igual que ayer
                <?php endif; ?>
            </h6>
        </div>
    </div>
</div>

<!--<div class="col-xl-4 col-md-6 col-sm-12">
    <div class="card analytic-card">
        <div class="card-body">
            <div class="row align-items-center m-b-25">
                <div class="col-auto">
                    <i class="fas fa-comment-dots text-primary f-18 analytic-icon"></i>
                </div>
                <div class="col text-end">
                    <h3 class="m-b-5 text-white">???</h3>
                    <h6 class="m-b-0 text-white">Comentarios totales</h6>
                </div>
            </div>
            <p class="m-b-0  text-white d-inline-block">Pendientes de responder: </p>
            <h5 class=" text-white d-inline-block m-b-0 m-l-10">???</h5>
        </div>
    </div>
</div>-->

<div class="col-xl-6 col-md-6 col-sm-12">
    <div class="card analytic-card">
        <div class="card-body">
            <div class="row align-items-center m-b-25">
                <div class="col-auto">
                    <i class="fas fa-book text-primary f-18 analytic-icon"></i>
                </div>
                <div class="col text-end">
                    <h3 class="m-b-5 text-white">???</h3>
                    <h6 class="m-b-0 text-white">Novelas activas</h6>
                </div>
            </div>
            <p class="m-b-0  text-white d-inline-block">Inactivas: </p>
            <h5 class=" text-white d-inline-block m-b-0 m-l-10">???</h5>
            <h6 class="m-b-0 d-inline-block  text-white float-end">
                <span class="text-success font-extrabold">
                    <i class="feather icon-arrow-up f-18"></i>
                        ??%
                </span> vs Mes anterior
            </h6>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card overflow-hidden">
        <div class="card-body">
            <h5 class="mb-3">Análisis de las vistas</h5>
            <div class="row">
                <div class="col-3">
                    <p class="text-muted m-b-5">Ayer</p>
                    <h6><?= htmlspecialchars($dashboard->ViewsYesterday ?? 0) ?></h6>
                </div>
                <div class="col-2">
                    <p class="text-muted m-b-5">Esta semana</p>
                    <h6><?= htmlspecialchars($dashboard->ViewsCurrentWeek ?? 0) ?></h6>
                </div>
                <div class="col-3">
                    <p class="text-muted m-b-5">La semana pasada</p>
                    <h6><?= htmlspecialchars($dashboard->ViewsLastWeek ?? 0) ?></h6>
                </div>
                <div class="col-2">
                    <p class="text-muted m-b-5">Este mes</p>
                    <h6><?= htmlspecialchars($dashboard->ViewsCurrentMonth ?? 0) ?></h6>
                </div>
                <div class="col-2">
                    <p class="text-muted m-b-5">El mes anterior</p>
                    <h6><?= htmlspecialchars($dashboard->ViewsLastMonth ?? 0) ?></h6>
                </div>
            </div>
        </div>
        <div id="income-analysis" class="<?= count($data->ViewsOfLast30Day) >= 2 ? '' : 'd-none' ?>"
             style="min-height: 100px">

        </div>
    </div>
</div>

<div class="col-md-8">
    <div class="card table-card">
        <div class="card-header">
            <h5>Top 5: Novelas más vistas</h5>
        </div>
        <div class="card-body px-0 py-0">
            <div class="table-responsive">
                <div class="product-scroll" style="max-height:295px;position:relative;">
                    <table class="table table-hover m-b-0">
                        <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Votos</th>
                            <th>Vistas</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $project_views = array_map(fn(ProjectItem $x) => $x->Views, $data->Top5Projects);

                        foreach ($data->Top5Projects as $project): ?>
                            <tr>
                                <td>
                                    <a href="<?= Url::Combine('/', $data->CurrentCircle->Name, $project->Name) ?>">
                                        <?= htmlspecialchars($project->Title) ?>
                                    </a>
                                </td>
                                <td>-</td>
                                <td>
                                    <?php
                                    $total_view = array_sum($project_views);
                                    $perc_view = !empty($project_views) && $total_view !== 0 ? $project->Views / $total_view : 0;
                                    ?>
                                    <?= htmlspecialchars($project->Views) ?>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-primary rounded" role="progressbar"
                                             style="width: <?= htmlspecialchars($perc_view * 100) ?>%;"
                                             aria-valuenow="<?= htmlspecialchars($perc_view * 100) ?>" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card table-card">
        <div class="card-header">
            <h5>Top 10: Capítulos más vistos</h5>
        </div>
        <div class="card-body px-0 py-0">
            <div class="table-responsive">
                <div class="product-scroll" style="max-height:295px;position:relative;">
                    <table class="table table-hover m-b-0">
                        <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Novela</th>
                            <th>Vistas</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $entries_views = array_map(fn(EntryItem $x) => $x->Views, $data->Top10Entries);

                        foreach ($data->Top10Entries as $entry): ?>
                            <tr>
                                <td>
                                    <a href="<?= htmlspecialchars(Url::Combine('/', $data->CurrentCircle->Name, $entry->ProjectName, $entry->Name)) ?>">
                                        <?= htmlspecialchars($entry->Title) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= htmlspecialchars(Url::Combine('/', $data->CurrentCircle->Name, $entry->ProjectName)) ?>">
                                        <?= htmlspecialchars($entry->ProjectTitle) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $total_view = array_sum($entries_views);
                                    $perc_view = !empty($entries_views) && $total_view !== 0 ? $entry->Views / $total_view : 0;
                                    ?>
                                    <?= htmlspecialchars($entry->Views) ?>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-primary rounded" role="progressbar"
                                             style="width: <?= htmlspecialchars($perc_view * 100) ?>%;"
                                             aria-valuenow="<?= htmlspecialchars($perc_view * 100) ?>" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card background-pattern">
        <div class="card-header borderless">
            <h5>Dispositivos</h5>
        </div>
        <div class="card-body pt-1 pb-3">
            <div class="row mt-3 text-center">
                <div class="col">
                    <h3 class="m-0"><i class="fas fa-circle f-10 m-r-5 text-primary"></i>???</h3>
                    <span class="ms-3">Escritorio</span>
                </div>
                <div class="col">
                    <h3 class="m-0"><i class="fas fa-circle text-primary f-10 m-r-5 text-success"></i>???</h3>
                    <span class="ms-3">Móviles</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card table-card">
        <div class="card-header borderless">
            <h5>Navegadores</h5>
        </div>
        <div class="card-body px-0 py-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <tbody>
                    <tr>
                        <td>Google Chrome</td>
                        <td><span class="text-end d-block m-0"><span class="m-r-15">??%</span><span
                                        class="data-attributes"
                                        data-peity='{ "fill": ["#5052FC", "#eeeeee"],"innerRadius": 8, "radius": 13 }'>5/7</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Mozilla Firefox</td>
                        <td><span class="text-end d-block m-0"><span class="m-r-15">??%</span><span
                                        class="data-attributes"
                                        data-peity='{ "fill": ["#ED4C13", "#eeeeee"],"innerRadius": 8, "radius": 13 }'>3/8</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Apple Safari</td>
                        <td><span class="text-end d-block m-0"><span class="m-r-15">??%</span><span
                                        class="data-attributes"
                                        data-peity='{ "fill": ["#06CA98", "#eeeeee"],"innerRadius": 8, "radius": 13 }'>5/6</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Internet Explorer</td>
                        <td><span class="text-end d-block m-0"><span class="m-r-15">??%</span><span
                                        class="data-attributes"
                                        data-peity='{ "fill": ["#7759de", "#eeeeee"],"innerRadius": 8, "radius": 13 }'>2/6</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Opera mini</td>
                        <td><span class="text-end d-block m-0"><span class="m-r-15">??%</span><span
                                        class="data-attributes"
                                        data-peity='{ "fill": ["#FF9800", "#eeeeee"],"innerRadius": 8, "radius": 13 }'>5/7</span></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const VIEWS_LAST_30_DAY = {
        Dates: JSON.parse('<?=json_encode(array_map(fn($x) => $x->TrackDate, $data->ViewsOfLast30Day))?>'),
        Count: JSON.parse('<?=json_encode(array_map(fn($x) => $x->Views, $data->ViewsOfLast30Day))?>')
    };
</script>