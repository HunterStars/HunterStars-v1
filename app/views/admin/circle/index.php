<?php use HS\app\models\items\CircleItem;
use HS\config\enums\SubDomains;
use HS\libs\view\View; ?>

<div class="col-sm-12">
    <ul class="nav nav-pills mb-4" id="tabs-circle-type" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-uppercase active" data-bs-toggle="tab">Todos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" data-bs-toggle="tab" data-circle-type="anime">Anime</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" data-bs-toggle="tab" data-circle-type="manga">Manga</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" data-bs-toggle="tab" data-circle-type="music">Musica</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" data-bs-toggle="tab" data-circle-type="novel">Novelas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" data-bs-toggle="tab" data-circle-type="game">Juegos</a>
        </li>
        <li class="col text-right">
            <button class="btn btn-primary d-flex align-items-center ms-auto" data-bs-toggle="modal"
                    data-bs-target="#modal-circle">
                <i class="me-2" data-feather="plus-circle"></i>Crear circulo
            </button>
        </li>
    </ul>
    <section>
        <div class="row mb-n4">
            <?php foreach (View::GetData()->Circles as $circle): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card user-card user-card-2 shape-center"
                         data-circle-type="<?= htmlspecialchars($circle->TypeName) ?>">
                        <div class="card-header border-0 p-2 pb-0">
                            <div class="cover-img-block">
                                <img src="<?= $circle->GetCoverUrl() ?>?h=220" style="max-height: 220px" alt=""
                                     class="img-fluid">
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="user-about-block text-center">
                                <div class="row align-items-end">
                                    <div class="col  pb-3"><a href="#"><i
                                                    class="icon feather icon-star-on text-warning f-20"></i></a>
                                    </div>
                                    <div class="col">
                                        <?php if (!empty($circle->ProfileImg)): ?>
                                            <div class="position-relative d-inline-block">
                                                <img class="img-radius img-fluid wid-80"
                                                     src="<?= $circle->GetProfileUrl() ?>?w=90" style="max-width: 90px"
                                                     alt="User image">
                                                <div class="certificated-badge">
                                                    <i class="fas fa-certificate text-primary bg-icon"></i>
                                                    <i class="fas fa-check front-icon text-white"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col text-end pb-3">
                                        <div class="dropdown">
                                            <a class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false"><i
                                                        class="feather icon-more-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Abandonar circulo</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h6 class="mb-1 mt-3"><?= htmlspecialchars($circle->Title) ?></h6>
                                <p class="mb-0 text-muted">@<?= htmlspecialchars($circle->Name) ?></p>
                                <a class="btn  btn-info m-t-30" href="/<?= $circle->Name ?>">
                                    <i class="fa fa-briefcase" style="margin-right: 5px; line-height: normal"></i>
                                    <span>Administrar</span>
                                </a>
                            </div>
                            <hr class="wid-80 pt-1 mx-auto my-4">
                            <div class="row text-center">
                                <div class="col">
                                    <h6 class="mb-1"><?= $circle->ProjectsCount ?></h6>
                                    <p class="mb-0"><?= htmlspecialchars($circle->GetNaturalTypeName()) ?></p>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1"><?= $circle->MembersCount ?></h6>
                                    <p class="mb-0">Miembro<?= $circle->MembersCount != 1 ? 's' : '' ?></p>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">???</h6>
                                    <p class="mb-0">Seguidores</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="warning-create-circle" class="col-sm-12 p-0 mt-5"
             style="display: <?= count(View::GetData()->Circles) > 0 ? 'none' : 'block' ?>">
            <div class="text-center pt-5">
                <h1 class="text-white text-uppercase">No perteneces a ningún círculo de este tipo</h1>
                <h5 class="text-white font-weight-normal mt-4 m-b-30">
                    No has creado ni eres miembro de ningún círculo, pero si deseas aportar contenido a la comunidad,
                    puedes crear uno.
                </h5>
                <button class="btn btn-primary d-flex align-items-center mb-4 ms-auto me-auto" data-bs-toggle="modal"
                        data-bs-target="#modal-circle">
                    <i class="me-2" data-feather="plus-circle"></i>Crear circulo
                </button>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-circle" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-dot-circle me-2"></i>Circulo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="circle-form" action="/circle">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="group-title">Nombre</label>
                                <input id="group-title" name="title" type="text" class="form-control"
                                       minlength="2" maxlength="150" pattern="<?= CircleItem::REGEX_TITLE ?>"
                                       title="- Mínimo 2 caracteres y máximo 150.&#10;- Solo se permite letras, números, y símbolos de puntuación."
                                       autocomplete="off"
                                       required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group fill">
                                <label class="form-label" for="circle-url">Enlace</label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="circle-url">
                                        https://<?= SubDomains::root->value ?>/circles/
                                    </label>
                                    <input id="circle-url" name="url" type="text" class="form-control"
                                           minlength="2" maxlength="50" pattern="<?= CircleItem::REGEX_NAME ?>"
                                           title="- Mínimo 2 caracteres y máximo 50.&#10;&#10;Solo se permite letras, números y guiones."
                                           autocomplete="off"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label" for="circle-type">Tipo de contenido</label>
                                <select class="form-select" id="circle-type" name="type">
                                    <?php foreach (View::GetData()->CircleTypes as $type): ?>
                                        <option value="<?= $type->ID ?>"><?= htmlspecialchars(CircleItem::NaturalTypeName[$type->Name] ?? $type->Name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <small class="mb-3">Solo podrá crear páginas de este tipo en este círculo.</small>
                        </div>
                        <div class="info col-12 mt-4">
                            <span class="text-muted">
                                <span class="h6 d-block">Información</span>
                                <span class="info-only-for-new">
                                    Al guardar será redirigido al sitio donde podrá completar toda la información sobre este elemento.
                                    <br/><br/>
                                    Ahi podrá:
                                </span>
                            </span>
                            <ul class="mt-1 mb-0">
                                <li>
                                    <small class="text-sm text-muted">
                                        Establecer una foto de perfil y una de portada.
                                    </small>
                                </li>
                                <li>
                                    <small class="text-sm text-muted">
                                        Crear paginas para su circulo.
                                    </small>
                                </li>
                                <li>
                                    <small class="text-sm text-muted">
                                        Ver estadísticas sobre sus páginas, etc.
                                    </small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 pt-1 pb-1">
                    <div class="row">
                        <div class="col-12">
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
</div>
