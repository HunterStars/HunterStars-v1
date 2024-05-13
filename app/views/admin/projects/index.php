<?php

use HS\app\models\items\ProjectItem;
use HS\config\enums\SubDomains;
use HS\libs\helpers\UrlFiles;
use HS\libs\view\View;

$viewData = View::GetData();
$circle = $viewData->CurrentCircle;
$circle_type = $viewData->CurrentCircle->TypeName;
?>
<style>
    #content-list tr img {
        border-radius: 20%;
    }
</style>

<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <div class="row align-items-center m-l-0">
                <h5 class="col-sm-6"><?= htmlspecialchars($circle->GetNaturalTypeName()) ?></h5>
                <div class="col-sm-6 text-end">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-report">
                        <i class="feather icon-plus"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="content-list" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Estado</th>
                        <th>Modificado por</th>
                        <th>Modificado en</th>
                        <th class="text-end">Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($viewData->Projects as $project): ?>
                        <tr>
                            <td>
                                <div class="d-inline-block align-middle">
                                    <img src="<?= $project->GetCoverUrl($circle->Name) . '?h=70' ?>"
                                         alt="Portada"
                                         class="wid-50 align-top m-r-15">

                                    <div class="d-inline-block">
                                        <h6><?= $project->Title ?></h6>
                                        <p class="text-muted m-b-5">
                                            <?= $project->GetCreatorName() ?>
                                        </p>
                                        <p class="text-muted m-b-0">
                                            <i data-feather="eye"></i>
                                            <span><?= $project->Views ?></span>
                                            /
                                            <i data-feather="message-square"></i>
                                            <span>?</span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light-success"><?= $project->StateName ?></span></td>
                            <td><?= $project->GetModifierName() ?></td>
                            <td><?= $project->GetModifiedDate() ?></td>
                            <td class="text-end">
                                <a href="/<?= urlencode($viewData->CurrentCircle->Name) ?>/<?= urlencode($project->Name) ?>"
                                   class="btn  btn-secondary btn-sm">
                                    <i data-feather="edit-2"></i> Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!--
                <div class="text-end m-r-20">
                    <a href="#" class=" b-b-primary text-primary">View all Projects</a>
                </div>-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-report" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($circle->GetSingularTypeName()) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="novel-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="novel-title">Titulo</label>
                                <input id="novel-title" name="title" type="text" class="form-control"
                                       minlength="2" maxlength="150"
                                       pattern="<?= ProjectItem::REGEX_TITLE ?>"
                                       title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, y símbolos de puntuación."
                                       autocomplete="off"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group fill">
                                <label class="form-label" for="novel-title-alt">Titulo Alternativo</label>
                                <input id="novel-title-alt" name="title_alt" type="text" class="form-control"
                                       minlength="2" maxlength="150"
                                       pattern="<?= ProjectItem::REGEX_TITLE ?>"
                                       title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, guiones y símbolos de puntuación."
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group fill">
                                <label class="form-label" for="novel-url">Enlace</label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="novel-url">
                                        https://<?= SubDomains::root->value ?>/novels/p/
                                    </label>
                                    <input id="novel-url" name="url" type="text" class="form-control"
                                           minlength="2" maxlength="50" pattern="<?= ProjectItem::REGEX_URL ?>"
                                           title="- Minimo 2 caracteres y maximo 50.&#10;- Solo se permite letras, números y guiones."
                                           autocomplete="off"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="novel-state">Estado</label>
                                <select class="form-select" id="novel-state" name="state">
                                    <?php foreach ($viewData->ProjectTypes as $type): ?>
                                        <option value="<?= $type->ID ?>"><?= $type->Name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                Al guardar será redirigido al sitio donde podrá completar toda la información sobre este
                                elemento.
                            </h6>
                        </div>
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-primary" value="Guardar"/>
                            <input type="reset" class="btn btn-danger" value="Limpiar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>