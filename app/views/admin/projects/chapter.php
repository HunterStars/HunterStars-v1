<?php

	use HS\libs\view\View;

	$entry = View::GetData()->Entry ?? null;
?>
<div class="col-xl-12 col-md-12">
    <form id="save-form">
        <div class="card">
            <h5 class="card-header">
            <span class="row">
                <span class="col d-flex flex-column">
                    <span class="mt-auto">Editor de capítulos</span>
                    <small id="save-status" class="text-muted text-sm mb-auto mt-1" style="font-size: .85rem">
                        <?php if (is_null($entry)): ?>
                            Nueva entrada
						<?php else: ?>
                            Modificado por <?= htmlspecialchars($entry->GetModifierName()) ?> el <?= $entry->GetModifiedDateTime() ?>
						<?php endif; ?>
                    </small>
                </span>
                <span class="col-auto">
                    <button type="submit" class="btn btn-primary float-end pt-2 pb-2 ps-3 pe-3" disabled>
                        <span class="fas fa-save me-1" style="vertical-align: middle"></span>
                        <span>Guardar</span>
                    </button>
                </span>
            </span>
            </h5>

            <div class="card-body">
                <div class="document-editor">
                    <div class="document-editor__toolbar"></div>
                    <div class="document-editor__editable-container">
                        <div class="document-editor__editable">
                            <h1><?= htmlspecialchars($entry->Title ?? '') ?></h1>
							<?= $entry->Content ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer p-t-15 p-b-15 ck-toolbar-container">
                <span class="row">
                    <span class="col d-flex flex-column">
                        <small class="text-muted text-sm">
                            <?php if (!is_null($entry)): ?>
                                Creado por <?= htmlspecialchars($entry->GetCreatorName()) ?> el <?= $entry->GetCreatedDate() ?>
							<?php endif; ?>
                        </small>
                    </span>
                    <span id="word-count" class="col-auto ck ck-toolbar" style="background: none">
                        <small class="text-muted text-sm"></small>
                        <span class="ck ck-toolbar__separator ms-2 me-2"></span>
                        <small class="text-muted text-sm"></small>
                    </span>
                </span>
            </div>
        </div>
    </form>

    <div class="card">
        <h5 class="card-header p-0">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#config-options" aria-expanded="false" aria-controls="collapseOne">
                Opciones de publicación
            </button>
        </h5>
        <form id="novel-form">
            <div id="config-options" class="card-body collapse">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="novel-url">Enlace</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="novel-url">
                                    https:///novels/p/
                                </label>
                                <input id="novel-url" name="url" type="text" class="form-control"
                                       minlength="2" maxlength="50" pattern="^[A-Za-z0-9-]{2,50}$"
                                       title="- Minimo 2 caracteres y maximo 50.&#10;- Solo se permite letras, números y guiones."
                                       autocomplete="off"
                                       value=""
                                       required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>