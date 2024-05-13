<?php

use HS\app\models\items\EntryItem;
use HS\app\models\items\GroupItem;
use HS\app\models\items\ProjectItem;
use HS\config\enums\SubDomains;
use HS\libs\helpers\HTML;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Url;
use HS\libs\view\View;

$viewData = View::GetData();
$project = $viewData->Project;
$empty_groups = $viewData->Groups;

//Ordenando entradas.
$entries = [];
foreach ($viewData->Entries as $entry) {
    if ($entry->Group != 0) {
        $group = "G-$entry->Group";
        if (!isset($entries[$group]))
            $entries[$group] = [];
        $entries[$group][] = $entry;
    } else
        $entries[] = $entry;
}

//Ordenando grupos de las entradas.
$entries_groups = [];
foreach ($empty_groups as $index => $group) {
    $group_key = "G-$group->ID";
    if (isset($entries[$group_key])) {
        $entries_groups[$group_key] = $group;
        unset($empty_groups[$index]);
    }
}
?>

    <style>
        .uppy-Dashboard-Item-action--edit {
            display: none;
        }

        .uppy-Dashboard-AddFiles-info {
            display: block;
        }

        a.uppy-Dashboard-poweredBy {
            display: none;
        }
    </style>

    <div class="col-xl-12 col-md-12">
        <div class="card">
            <h5 class="card-header">
                Datos Generales<br/>
                <span class="text-muted mb-0 mt-1"
                      style="font-size: .85rem"><?= sprintf("Ultima modificación el %s por %s",
                        htmlspecialchars($project->GetModifiedDate()), htmlspecialchars($project->GetModifierName())) ?></span>
            </h5>
            <form id="novel-form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="uppy-Root" dir="ltr">
                                        <div class="uppy-Dashboard uppy-Dashboard--animateOpenClose uppy-Dashboard--isInnerWrapVisible"
                                             data-uppy-theme="dark" data-uppy-num-acquirers="0"
                                             data-uppy-drag-drop-supported="true" aria-hidden="false"
                                             aria-disabled="false"
                                             aria-label="Uppy Dashboard">
                                            <div aria-hidden="true" class="uppy-Dashboard-overlay" tabindex="-1"></div>
                                            <div class="uppy-Dashboard-inner" aria-modal="false" role="false"
                                                 style="width: 750px; height: 260px;">
                                                <div class="uppy-Dashboard-innerWrap">
                                                    <div class="uppy-Dashboard-AddFiles">
                                                        <div class="uppy-Dashboard-AddFiles-info pt-2 pb-1 w-100"
                                                             style="display: block; border-bottom: 1px solid #333;">
                                                            <div class="uppy-Dashboard-note">Portada Actual</div>
                                                        </div>
                                                        <div class="uppy-Dashboard-AddFiles-list d-block mt-1"
                                                             role="tablist">
                                                            <img src="<?= $project->GetCoverUrl($viewData->CurrentCircle->Name) . '?h=165' ?>"
                                                                 class="cover h-100 w-auto"
                                                                 alt="Portada">
                                                        </div>
                                                        <div class="uppy-Dashboard-AddFiles-title mt-1"
                                                             style="border-top: 1px solid #333;">
                                                            <button type="button"
                                                                    class="uppy-u-reset uppy-c-btn uppy-Dashboard-browse mt-2 uppy-btn-show-modal">
                                                                Subir Nueva Portada
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uppy-files-drag-drop" data-target="cover"></div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="novel-title">Titulo</label>
                                                <input id="novel-title" name="title" type="text" class="form-control"
                                                       minlength="2" maxlength="150"
                                                       pattern="<?=ProjectItem::REGEX_TITLE?>"
                                                       title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, y símbolos de puntuación."
                                                       autocomplete="off"
                                                       value="<?= $project->Title ?? '' ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group fill">
                                                <label class="form-label" for="novel-title-alt">Titulo
                                                    Alternativo</label>
                                                <input id="novel-title-alt" name="title_alt" type="text"
                                                       class="form-control"
                                                       minlength="2" maxlength="150"
                                                       pattern="<?=ProjectItem::REGEX_TITLE?>"
                                                       title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, guiones y símbolos de puntuación."
                                                       value="<?= $project->TitleAlt ?? '' ?>"
                                                       autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="novel-state">Estado</label>
                                                <select class="form-select" id="novel-state" name="state">
                                                    <?php foreach ($viewData->ProjectTypes as $type): ?>
                                                        <option value="<?= $type->ID ?>" <?= $project->StateID == $type->ID ? 'selected' : '' ?>>
                                                            <?= $type->Name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="novel-url">Enlace</label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="novel-url">
                                        https://<?= SubDomains::root->value ?>/novels/p/
                                    </label>
                                    <input id="novel-url" name="url" type="text" class="form-control"
                                           minlength="2" maxlength="50" pattern="^[A-Za-z0-9-]{2,50}$"
                                           title="- Minimo 2 caracteres y maximo 50.&#10;- Solo se permite letras, números y guiones."
                                           autocomplete="off"
                                           value="<?= urlencode($project->Name) ?>"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group fill">
                                <label class="form-label" for="novel-categories">Categorías</label>
                                <input id="novel-categories" name="categories" type="text" class="form-control"
                                       style="display: none"
                                       value='<?= json_encode(array_map(fn($x) => ['code' => $x->ID, 'value' => htmlspecialchars($x->Name)], $project->Categories)); ?>'>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label" for="sinopsis-editor">Sinopsis</label>
                                <div id="sinopsis-editor">
                                    <?= $project->Synopsis ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-t-15 p-b-15">
                    <div class="row">
                        <div class="col-8 d-flex">
                            <small class="text-muted text-sm mt-auto mb-auto"><?= sprintf("Creado el %s por %s",
                                    htmlspecialchars($project->GetCreatedDate()), htmlspecialchars($project->GetCreatorName())) ?></small>
                        </div>
                        <div class="col-4">
                            <input type="submit" class="btn btn-primary float-end" value="Guardar"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card table-card">
            <h5 class="card-header">
            <span class="row align-items-center m-l-0">
                <span class="col-sm-6">Capítulos</span>
                <span class="col-sm-6 text-end">
                    <button id="btn-sort-caps" class="btn btn-icon btn-sm"
                            title="Cambiar orden de los capítulos"
                            data-bs-toggle="collapse" data-bs-target="#sort-caps-toolbar">
                        <i class="fas fa-sort-amount-down"></i> Ordenar
                    </button>
                    <span class="btn-group">
                        <a class="btn btn-sm btn-primary" href="<?= urlencode($project->Name) ?>/chapter">
                            <i class="feather icon-plus"></i> Nuevo
                        </a>
                         <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"
                                 data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                     class="sr-only">Opciones</span></button>

							<span class="dropdown-menu" style="">
								<a class="dropdown-item" href="<?= urlencode($project->Name) ?>/chapter">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Capítulo
                                </a>
                                <span class="dropdown-divider"></span>
								<button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-group">
                                    <i class="fas fa-book me-2"></i>
                                    Volumen / Arco Argumental</button>
							</span>
						</span>
                </span>
            </h5>
            <div class="card-body pb-0">
                <div id="sort-caps-toolbar" class="ck-toolbar-container collapse">
                <span class="ck ck-toolbar ps-3">
                    <span>
                        <span class="text-muted font-bold d-block">
                        Se habilito el modo de ordenamiento.
                        </span>
                        <small class="text-muted text-sm">
                        En este modo podrá arrastrar los capítulos de la lista para cambiar el orden en que se listaran en la pagina de la novela.
                        </small>
                    </span>

                    <span class="ck ck-toolbar__separator ms-2 me-2 ms-auto"></span>
                    <button id="btn-apply-sort-caps" class="btn btn-sm btn-outline-primary mt-2 mb-2">
                        <i class="fa fa-check"></i> Aplicar cambios
                    </button>
                </span>
                </div>
                <div id="simpleList">
                    <ul class="list-group">
                        <?php foreach ($empty_groups as $group) {
                            WriteSortableGroup($group, []);
                        } ?>

                        <?php
                        foreach ($entries as $index => $entry) {
                            if (is_int($index))
                                WriteSortableItem($entry);
                            else
                                WriteSortableGroup($entries_groups[$index], $entry);
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-group" tabindex="-1" role="dialog"
         aria-labelledby="Volúmenes o Arcos Argumentales"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-book me-2"></i> Volumen o Arco Argumental
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form id="group-form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="group-title">Titulo</label>
                                    <input id="group-title" name="title" type="text" class="form-control"
                                           minlength="2" maxlength="150"
                                           pattern=".*"
                                           title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, y símbolos de puntuación."
                                           autocomplete="off"
                                           required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="uppy-Root" dir="ltr">
                                    <div class="uppy-Dashboard uppy-Dashboard--animateOpenClose uppy-Dashboard--isInnerWrapVisible"
                                         data-uppy-theme="dark" data-uppy-num-acquirers="0"
                                         data-uppy-drag-drop-supported="true" aria-hidden="false" aria-disabled="false"
                                         aria-label="Uppy Dashboard">
                                        <div aria-hidden="true" class="uppy-Dashboard-overlay" tabindex="-1"></div>
                                        <div class="uppy-Dashboard-inner" aria-modal="false" role="false"
                                             style="width: 750px; height: 260px;">
                                            <div class="uppy-Dashboard-innerWrap">
                                                <div class="uppy-Dashboard-AddFiles">
                                                    <div class="uppy-Dashboard-AddFiles-info pt-2 pb-1 w-100"
                                                         style="display: block; border-bottom: 1px solid #333;">
                                                        <div class="uppy-Dashboard-note">Portada Actual</div>
                                                    </div>
                                                    <div class="uppy-Dashboard-AddFiles-list d-block mt-1"
                                                         role="tablist">
                                                        <img src="" class="cover h-100 w-auto"
                                                             alt="Portada">
                                                    </div>
                                                    <div class="uppy-Dashboard-AddFiles-title mt-1"
                                                         style="border-top: 1px solid #333;">
                                                        <button type="button"
                                                                class="uppy-u-reset uppy-c-btn uppy-Dashboard-browse mt-2 uppy-btn-show-modal">
                                                            Subir Nueva Portada
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uppy-files-drag-drop" data-target="group/cover"></div>
                            </div>
                            <div class="info col-12">
                            <span class="text-muted">
                                <span class="h6 d-block">Información</span>
                                <span class="info-only-for-new">Al crear un volumen o arco argumental podrá agrupar capítulos dentro de este.</span>
                            </span>
                                <ul class="mt-1 mb-0">
                                    <li>
                                        <small class="text-sm text-muted">
                                            Ingrese al modo de ordenamiento para agrupar capítulos.
                                        </small>
                                    </li>
                                    <li>
                                        <small class="text-sm text-muted">Los volúmenes vacíos siempre se muestran al
                                            principio de la lista, los que tienen capítulos agrupados mantienen su
                                            posición
                                            de ordenamiento.</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-0 pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <input type="submit" class="btn btn-primary float-end" value="Guardar"/>
                                <!--<input type="reset" class="btn btn-danger" value="Limpiar"/>-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php function WriteSortableGroup(GroupItem $group, array $entries)
{ ?>
    <li class="sortable-group p-0" data-sort-id="G-<?= $group->ID ?>">
        <div class="list-group-item d-flex flex-grow-1 align-items-center">
            <span class="fas fa-grip-lines-vertical align-self-center text-muted"></span>

            <div class="d-flex flex-wrap flex-grow-1">
                <img src="<?= !empty($group->Cover) ?
                    UrlFiles::GetProjectAdminIMG(View::GetData()->CurrentCircle->Name, View::GetData()->Project->Name, urlencode($group->Cover)) : '/files/img/basic/novel-cover.png'; ?>?h=70"
                     alt="Portada"
                     class="wid-50 align-top m-r-15">

                <div class="mt-auto mb-auto me-auto">
                    <h6 class="group-title fw-bold m-0"><?= htmlspecialchars($group->Title) ?></h6>
                    <button class="btn-edit-group btn btn-sm ps-0 pb-0">
                        <i class="fas fa-edit fa-sm"></i> <u>Editar</u>
                    </button>
                </div>

                <div class="d-flex flex-column flex-grow-0 justify-content-end ms-auto">
                </div>
            </div>
        </div>
        <ul class="list-group">
            <?php
            foreach ($entries as $entry)
                WriteSortableItem($entry);
            ?>
        </ul>
    </li>
<?php }

function WriteSortableItem(EntryItem $entry): void
{ ?>
    <li class="p-0" data-sort-id="<?= htmlspecialchars($entry->Name) ?>">
        <div class="list-group-item d-flex flex-grow-1 align-items-center">
            <span class="fas fa-grip-lines-vertical align-self-center text-muted"></span>

            <div class="d-flex flex-wrap flex-grow-1">
                <div class="mt-auto mb-auto me-auto">
                    <div class="fw-bold"><?= htmlspecialchars($entry->Title) ?></div>
                    Capítulo <?= htmlspecialchars($entry->Name) ?>
                </div>

                <div class="d-flex flex-column flex-grow-0 justify-content-end ms-auto">
                    <small class="mb-1">
                        <?= $entry->CreatedAt === $entry->ModifiedAt ? 'Publicado' : 'Modificado' ?>
                        el <?= htmlspecialchars($entry->GetModifiedDateTime(time: false)) ?>
                    </small>
                    <a href="<?= Url::Combine(urlencode(View::GetData()->Project->Name), urlencode($entry->Name)) ?>"
                       class="btn btn-secondary btn-sm align-self-end d-block">
                        <i data-feather="edit-2"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </li>
<?php } ?>