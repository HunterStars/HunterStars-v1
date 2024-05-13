//Comments.
function registerCommentArea(writer) {
    const textarea = $('textarea', writer)[0];
    const btn_submit = $('button[type="submit"]', writer)[0];

    textarea.addEventListener('focus', () => writer.lastElementChild.classList.remove('d-none'));
    textarea.addEventListener('keyup', function () {
        if (textarea.value.trim() === '') btn_submit.setAttribute(ATTR_DISABLED, ''); else btn_submit.removeAttribute(ATTR_DISABLED);
    });
    writer.addEventListener('click', (ev) => ev.stopPropagation());
    writer.addEventListener('submit', function (ev) {
        ev.preventDefault();

        if (textarea.value.trim() !== '') {
            const form = this;
            const data = new FormData(form);
            if (form.hasAttribute('data-code')) data.set('code', form.getAttribute('data-code'));

            DoRequest('POST', form.getAttribute('action'), data, function (json) {
                const full_name = $('#extra-menu .account-user-shortname')[0].textContent;
                const content = $('textarea', form)[0].value.trim();
                const item = MakeCommentItem(json.code, full_name, content, json.isM);

                //Limpiando textarea.
                textarea.value = '';
                btn_submit.setAttribute(ATTR_DISABLED, '');

                //Nuevo comentario.
                //Añadiendo al principio de lista principal.
                if (form.parentElement.classList.contains('content'))
                    form.nextElementSibling.prepend(item);
                //Añadiendo al final de la sublista.
                else {
                    let list = form.closest('.list');
                    if (list.id === 'comment-list') {
                        const li = form.parentElement;
                        list = $('.list', li);

                        //Si no existe la sublista, crearla.
                        if (list.length === 0) {
                            list = document.createElement('ul');
                            list.classList.add('list');
                            li.append(list);
                        } else
                            list = list[0];
                    }

                    //Añadiendo comentario.
                    writer.remove();
                    list.parentElement.setAttribute('open', '');
                    list.append(item)
                }

                //Añadiendo efecto.
                AddHoverEffect($('.item', item)[0]);

                //Añadiendo eventos.
                $('.btn-reply', item)[0].addEventListener('click', onclick_btn_reply);

                //Si no esta visible, mostrarlo.
                if (!$isInViewport(item)) item.scrollIntoView({block: "end", behavior: "smooth"});
            }, null, null, null, false);
        }
    });
}

function MakeCommentItem(code, full_name, content, isM) {
    let li = document.createElement('li');
    let text = document.createTextNode(content);
    li.setAttribute('data-code', code);
    li.innerHTML = `<div class="item no-focus no-auto-expand-icon">
                                <figure>
                                    <img src="/files/img/basic/Errdex.png" alt="Perfil">
                                </figure>
                                <span class="text">
                                    <span class="mb-1">
                                        <span class="row">
                                            <span class="col full-name">${full_name}</span>
                                            <small class="col-auto created-date">Ahora</small>
                                        </span>
                                    </span>
                                     ${isM ? '<small><i class="m-icons">verified</i>Miembro del circulo</small>' : ''}
                                    <span class="comment-content mt-3"></span>
                                <div class="actions mt-1">
                                    <small>
                                    </small>
                                    <div>
                                        <button class="btn-text">Eliminar</button>
                                        <button class="btn-text btn-reply">Responder</button>
                                    </div>
                                </div>
                            </span>
                            </div>`;

    $('.comment-content', li)[0].append(text);
    return li;
}

const main_writer = $('.comments .writer')[0];
registerCommentArea(main_writer);

$('#comment-list .btn-reply').forEach(item => item.addEventListener('click', onclick_btn_reply));


//Controladores de eventos separados.
function onclick_btn_reply() {
    const item = this.closest('.item');

    if (!item.nextElementSibling || !item.nextElementSibling.classList.contains('writer')) {
        const writer = main_writer.cloneNode(true);
        writer.setAttribute('data-code', item.parentElement.getAttribute('data-code'));
        if (item.nextSibling) item.parentElement.insertBefore(writer, item.nextSibling); else item.parentElement.append(writer);
        registerCommentArea(writer);
    }
}