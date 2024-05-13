<?php

use HS\app\models\items\CircleItem;
use HS\config\enums\SubDomains;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Url;
use HS\libs\view\View;

$circle = View::GetData()->CurrentCircle;

?>
<div class="col-12 mb-4">
    <div class="uppy-Root" dir="ltr">
        <div class="uppy-Dashboard uppy-Dashboard--animateOpenClose uppy-Dashboard--isInnerWrapVisible w-100"
             data-uppy-theme="dark" data-uppy-num-acquirers="0"
             data-uppy-drag-drop-supported="true" aria-hidden="false"
             aria-disabled="false"
             aria-label="Uppy Dashboard">
            <div aria-hidden="true" class="uppy-Dashboard-overlay" tabindex="-1"></div>
            <div class="uppy-Dashboard-inner w-100 h-auto" aria-modal="false" role="false">
                <div class="uppy-Dashboard-innerWrap h-auto">
                    <div class="uppy-Dashboard-AddFiles h-auto">
                        <div class="uppy-Dashboard-AddFiles-list d-block p-0 m-0"
                             role="tablist">
                            <img src="<?= $circle->GetCoverUrl() ?>?w=1280"
                                 class="cover w-100 h-auto mb-1 <?= empty($circle->CoverImg) ? 'd-none' : '' ?>"
                                 alt="Portada">
                        </div>
                        <div class="uppy-Dashboard-AddFiles-title mt-0"
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
    <div class="uppy-files-drag-drop" data-aspect-ratio="5.33" data-target="img/cover"></div>
</div>

<div class="col-xl-12 col-md-12 mt-1">
    <div class="card">
        <form id="circle-form" action="<?= htmlspecialchars(Url::Combine('/', $circle->Name, 'settings')) ?>">
            <h5 class="card-header">
                Datos Generales
            </h5>
            <div class="card-body">
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
                                     style="width: 700px; height: 165px;">
                                    <div class="uppy-Dashboard-innerWrap">
                                        <div class="uppy-Dashboard-AddFiles">
                                            <div class="uppy-Dashboard-AddFiles-list d-block mt-1"
                                                 role="tablist">
                                                <img src="<?= $circle->GetProfileUrl() ?>?h=100"
                                                     class="cover h-100 w-auto" alt="Foto">
                                            </div>
                                            <div class="uppy-Dashboard-AddFiles-title mt-1"
                                                 style="border-top: 1px solid #333;">
                                                <button type="button"
                                                        class="uppy-u-reset uppy-c-btn uppy-Dashboard-browse mt-2 uppy-btn-show-modal">
                                                    Subir Nueva Foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uppy-files-drag-drop" data-aspect-ratio="1" data-target="img/profile"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="circle-title">Nombre</label>
                                    <input id="circle-title" name="title" type="text" class="form-control"
                                           minlength="2" maxlength="150"
                                           pattern="^[A-Za-zñáéíóú0-9-_¡!¿?\[\].,*\+&;: ]{2,150}$"
                                           title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, y símbolos de puntuación."
                                           autocomplete="off"
                                           value="<?= htmlspecialchars($circle->Title ?? '') ?>"
                                           required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="circle-type">Tipo de contenido</label>
                                    <select class="form-select" id="circle-type" name="type">
                                        <?php foreach (View::GetData()->CircleTypes as $type): ?>
                                            <option value="<?= $type->ID ?>" <?= $circle->TypeID == $type->ID ? 'selected' : '' ?>>
                                                <?= htmlspecialchars(CircleItem::NaturalTypeName[$type->Name] ?? $type->Name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="circle-url">Enlace</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="circle-url">
                                    https://<?= SubDomains::root->value ?>/circles/
                                </label>
                                <input id="circle-url" name="url" type="text" class="form-control"
                                       minlength="2" maxlength="50" pattern="^[A-Za-z0-9-]{2,50}$"
                                       title="- Minimo 2 caracteres y maximo 50.&#10;- Solo se permite letras, números y guiones."
                                       autocomplete="off"
                                       value="<?= htmlspecialchars($circle->Name ?? '') ?>"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label" for="desc-editor">Descripción</label>
                            <div id="desc-editor">
                                <?= $circle->Description ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer p-t-15 p-b-15">
                <div class="row">
                    <div class="col-8 d-flex">
                        <small class="text-muted text-sm mt-auto mb-auto">

                        </small>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary float-end">
                            <i class="spinner-border spinner-border-sm me-1 d-none"></i>
                            <span>Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>