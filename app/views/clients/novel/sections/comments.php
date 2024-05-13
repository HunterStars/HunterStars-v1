<?php

use HS\app\models\items\CommentItem;
use HS\libs\core\Session;
use HS\libs\helpers\DateUtils;
use HS\libs\io\Url;
use HS\libs\view\View;

$comments = View::GetClientData()->Comments;
?>

<form class="writer" action="<?= Url::Relative('/comment') ?>">
    <div class="d-flex flex-row">
        <figure>
            <img src="/files/img/basic/Errdex.png" alt="Perfil">
        </figure>
        <div class="input-text">
                            <textarea name="c-text" class="auto-resize scrollbar" cols="30" rows="2"
                                      placeholder="Agrega un comentario..."></textarea>
        </div>
    </div>

    <div class="row d-none">
        <?php if (!Session::GetOnlyRead()->IsLogin()): ?>
            <div class="col"></div>
            <div class="col-auto">
                <small>Debes <a href="/login">iniciar sesión</a> o <a href="/register">registrarte</a> para comentar.</small>
            </div>
        <?php else: ?>
            <div class="col"></div>
            <div class="col-auto">
                <button type="reset" class="btn-text">Cancelar</button>
                <button type="submit" class="btn-text" disabled>Comentar</button>
            </div>
        <?php endif; ?>
    </div>
</form>

<ul id="comment-list" class="list">
    <?php foreach ($comments[''] ?? [] as $comment):
        $sub_comments = $comments[$comment->ID] ?? [] ?>

        <li data-code="<?= htmlspecialchars($comment->GetID()) ?>">
            <?php comment_item($comment, count($sub_comments)); ?>

            <?php if (!empty($sub_comments)): ?>
                <ul class="list">
                    <?php foreach (array_reverse($sub_comments) as $sub_comment): ?>
                        <li data-code="<?= htmlspecialchars($comment->GetID()) ?>" open>
                            <?php comment_item($sub_comment, 0); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

<?php function comment_item(CommentItem $comment, int $count): void
{
    ?>
    <div class="item no-focus no-auto-expand-icon">
        <figure>
            <img src="/files/img/basic/Errdex.png" alt="Perfil">
        </figure>
        <div class="text">
                                    <span class="mb-1">
                                        <span class="row">
                                            <span class="col full-name"><?= htmlspecialchars($comment->GetShortName()) ?></span>
                                            <small class="col-auto"><?= htmlspecialchars(DateUtils::GetNaturalDateTime($comment->CreatedAt)) ?></small>
                                        </span>
                                    </span>
            <?php if ($comment->IsMember) : ?>
                <small>
                    <i class="m-icons">verified</i>Miembro del circulo
                </small>
            <?php endif; ?>

            <span class="comment-content mt-3">
                                        <?= htmlspecialchars($comment->Content) ?>
                                    </span>
            <div class="actions mt-1">
                <small>
                    <?php if ($count > 0): ?>
                        <i class="m-icons expand-icon m-0"></i><?= $count . ($count == 1 ? ' respuesta' : ' respuestas') ?>
                    <?php endif; ?>
                </small>
                <span>
                                        <!--<button class="btn-text">Eliminar</button>-->
                                        <button class="btn-text btn-reply">Responder</button>
                                    </span>

            </div>
        </div>
    </div>
<?php } ?>
