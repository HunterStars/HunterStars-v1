//Uppy Upload.
$('.uppy-files-drag-drop').forEach(function (uppy_box, index) {
    uppy_box.id = 'uppy-file-drag-drop-' + (index + 1);

    const uppy = GetUppyModalInstance(uppy_box.id, 0.71, window.location.href + '/' + uppy_box.getAttribute('data-target'), ['filename']);

    uppy.on('file-added', (file) => {
        uppy.setFileMeta(file.id, {
            filename: form_group.hasAttribute(ATTR_DATA_GROUP) ? form_group.getAttribute(ATTR_DATA_GROUP) : ''
        });
    });

    //El segundo upload.
    if (index === 1){
        uppy.on('upload-success', (file, response) => {
            const element = GetSelectedGroup(form_group.getAttribute(ATTR_DATA_GROUP));
            const img = $('img',element)[0];
            img.src = URL.createObjectURL(file.data);
        });
    }
});

//Choices en formulario.
new Choices($('#novel-state'), {
    searchEnabled: false, itemSelectText: 'Presiona para seleccionar', allowHTML: true
});

//Categories
let tagify_ajax = null;
const tagify = new Tagify($('#novel-categories'), {
    whitelist: [], maxTags: 10, dropdown: {
        maxItems: 6,           // <- mixumum allowed rendered suggestions
        classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
        enabled: 0,             // <- show suggestions on focus
        closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
    }, templates: {
        dropdownFooter(suggestions) {
            const hasMore = suggestions.length - this.settings.dropdown.maxItems;

            return hasMore > 0 ? `<footer data-selector='tagify-suggestions-footer' class="${this.settings.classNames.dropdownFooter}">
            Hay ${hasMore} categorias más. Escribe para especificar y reducir la busqueda.
          </footer>` : '';
        }
    }, validate: (tag) => {
        return /^[\wáéíóúÁÉÍÓÚñ ]+$/.test(tag.value);
    }
});

tagify.on('add', ({detail: {data, tag}}) => {
    tagify.tagLoading(tag, true)

    //Verificando si la etiqueta existe en la whitelist.
    if (tagify.whitelist.filter(x => x.value.toLowerCase() === data.value.toLowerCase()).length) {
        if (tagify.isTagDuplicate(data.value) > 1) tag.remove(); else tagify.tagLoading(tag, false)
        return;
    }

    //Subiendo al servidor la nueva etiqueta.
    ajax('POST', 'tags', {
        tag: data.value
    }, {
        load: function (ev) {
            tagify.tagLoading(tag, false)

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    tagify.replaceTag(tag, {...data, code: json.code, __isValid: true})
                } else {
                    if (json.error)
                        tagify.replaceTag(tag, {
                            ...data,
                            __isValid: json.error
                        });
                    else tagify.replaceTag(tag, {...data, __isValid: json.warning})
                }
            } else tagify.replaceTag(tag, {...data, __isValid: "Ha ocurrido un error al crear la etiqueta."})
        }, error: function () {
            tagify.replaceTag(tag, {...data, __isValid: "No fue posible comunicarse con el servidor"})
        }
    });
})
tagify.on('input', function (e) {
    const value = e.detail.value.trim();
    tagify.loading(true).dropdown.hide();

    //Si hay una petición en progreso, abortarla.
    if (tagify_ajax !== null) tagify_ajax.abort();

    //Si se escribe un carácter no valido.
    if (!/^[\wáéíóúÁÉÍÓÚñ ]+$/.test(e.detail.value)) {
        tagify.loading(false);
        return;
    }

    //Lanzando petición.
    tagify_ajax = ajax('GET', 'tags', {
        tag: value
    }, {
        load: function (ev) {
            tagify.loading(false);

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    tagify.whitelist = json.data;
                }
                tagify.dropdown.show(value);
            } else ShowSweetAlert('error', 'No fue posible buscar etiquetas.');
        }, error: function () {
            ShowSweetAlert('error', "No fue posible conectarse con el servidor.");
        }
    });
});

//Rich Text
// Create a watchdog for the given editor type.
const watchdog = new CKSource.EditorWatchdog(CKSource.Editor);
watchdog.create(document.querySelector('#sinopsis-editor'), {
    toolbar: {
        shouldNotGroupWhenFull: false
    }, language: {
        ui: 'es',
        content: 'es',
        textPartLanguage: [{title: "Español", languageCode: "es"}, {title: "English", languageCode: "en"}]
    },
    list: {
        properties: {
            styles: true,
            startIndex: true,
            reversed: true
        }
    }
});


//Events
$('#novel-form').addEventListener('submit', function (ev) {
    ev.preventDefault();

    const novel_url = $('#novel-url');
    const btn_submit = $('input[type="submit"]', this)[0];

    btn_submit.setAttribute('disabled', '');

    ajax('POST', location.href, {
        'title': $('#novel-title').value,
        'title_alt': $('#novel-title-alt').value,
        'url': novel_url.value,
        'state': $('#novel-state').value,
        'categories': JSON.stringify(tagify.value, ['code', 'value', '__isValid']),
        'synopsis': watchdog.editor.getData()
    }, {
        load: function (ev) {
            btn_submit.removeAttribute('disabled');

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    //Mostrando mensaje de confirmación.
                    ShowSweetAlert('success', 'Guardado correctamente');

                    //Reemplazando URL actual.
                    history.replaceState(null, '', json.url);
                } else {
                    if (json.warning !== undefined) {
                        switch (json.code) {
                            case 2:
                            case 4:
                                novel_url.focus();
                                break;
                        }
                        ShowSweetAlert('warning', json.warning);
                    } else if (json.error !== undefined)
                        ShowSweetAlert('error', json.error);
                }
            } else if (ev.target.status === 303) {
                window.location = '/';
            } else if (ev.target.status === 403)
                ShowSweetAlert('error', 'No tiene privilegios suficientes');
            else
                ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
        }, error: function () {
            btn_submit.removeAttribute('disabled');
            ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
        }
    });
});

//Ordenar capítulos.
const SORTABLE_OBJ = {
    AllLists: () => $('#simpleList .list-group'),
    AllItems: () => $('#simpleList .list-group-item'),
    forEachList: (method) => SORTABLE_OBJ.AllLists().forEach(method),
    forEachSortableGroup: (list, method) => $('.sortable-group', list).forEach((item, index) => method({
        header: item.firstElementChild,
        body: item.lastElementChild
    }, index)),
    ToggleSorting: () => {
        SORTABLE_OBJ.AllLists().forEach((list) => list.classList.toggle('sorting'));
        SORTABLE_OBJ.AllItems().forEach((item) => item.firstElementChild.classList.toggle('sort-handle'));
    },
    _backup_sort: new Map(),
    Backup: () => SORTABLE_OBJ.forEachList((list) => SORTABLE_OBJ._backup_sort.set(
        list.id, list.childElementCount > 0 ? Sortable.get(list).toArray() : [])),
    Restore: () => {
        //Identificando elementos faltantes en las listas y retornandolos a su lista original.
        SORTABLE_OBJ._backup_sort.forEach(function (value, key) {
            const list = $('#' + key);
            const new_order = Sortable.get(list).toArray();
            const lost_items = value.filter((x) => !new_order.includes(x))

            lost_items.forEach(x => list.appendChild($(`li[data-sort-id='${x}']`, list)[0]));
        });

        SORTABLE_OBJ.forEachList((list) => {
            if (SORTABLE_OBJ._backup_sort.has(list.id)) {
                const sortable = Sortable.get(list);
                sortable.sort(SORTABLE_OBJ._backup_sort.get(list.id), true);
                SORTABLE_OBJ._backup_sort.delete(list.id);
            }
        });
    },
    newSortableSubList: (list) => new Sortable(list, {
            ...SORTABLE_OBJ.Options,
            group: {
                name: 'nested',
                put: (to, from, drag) => !drag.classList.contains('sortable-group'),
                pull: true //Sacar
            }
        }
    ),
    Options: {
        handle: '.sort-handle',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        dataIdAttr: 'data-sort-id'
        /*
       store: {
            // Get the order of elements. Called once during initialization.
            * @param   {Sortable}  sortable
            * @returns {Array}
           get: function (sortable) {
               var order = localStorage.getItem(sortable.options.group.name);
               return order ? order.split('|') : [];
           },

            //Save the order of elements. Called onEnd (when the item is dropped).
            * @param {Sortable}  sortable
           set: function (sortable) {
               var order = sortable.toArray();
               localStorage.setItem(sortable.options.group.name, order.join('|'));
           }
       }
       */
    }
};

SORTABLE_OBJ.forEachList(function (list, index) {
    list.id = 'sortable-list-' + (index + 1);

    //Inicializando listas.
    if (index === 0) {
        new Sortable(list, {
            ...SORTABLE_OBJ.Options,
            group: 'nested'
        })
    } else
        SORTABLE_OBJ.newSortableSubList(list);

    //Inicializando listas con sublista.
    SORTABLE_OBJ.forEachSortableGroup(list, (group) => {
        InitializeCollapseGroup(group.header, group.body);

        //Inicializando botón de edición del grupo.
        const btn_edit = $('.btn-edit-group', group.header)[0];
        btn_edit.addEventListener('click', () => EditGroupBtn_OnClick(btn_edit, group.header.parentElement))
    });
});

function InitializeCollapseGroup(button, collapse_box, open = false) {
    button.classList.add('accordion-button');
    if (!open) button.classList.add('collapsed');
    collapse_box.classList.add('collapse');
    new bootstrap.Collapse(collapse_box, {toggle: open});

    button.addEventListener('click', function () {
        bootstrap.Collapse.getInstance(collapse_box).toggle();
        button.classList.toggle('collapsed');
    });
}

$('#btn-sort-caps').addEventListener('click', function () {
    SORTABLE_OBJ.forEachList(function (list) {
        if (list.classList.contains('sorting'))
            SORTABLE_OBJ.Restore();
        else
            SORTABLE_OBJ.Backup();
    });

    SORTABLE_OBJ.ToggleSorting();
})

$('#btn-apply-sort-caps').addEventListener('click', function () {
    const root_list = $('#simpleList > .list-group')[0];
    const btn_sort_mode = $('#btn-sort-caps');
    const btn_apply_sort = this;

    const allow_sort = (enable) => {
        if (enable) {
            //Habilitando ordenamiento.
            root_list.removeAttribute('disabled');
            Sortable.get(root_list).option("disabled", false);

            //Deshabilitando botones.
            btn_sort_mode.removeAttribute('disabled');
            btn_apply_sort.removeAttribute('disabled');
        } else {
            //Deshabilitando ordenamiento.
            root_list.setAttribute('disabled', '');
            Sortable.get(root_list).option("disabled", true);

            //Deshabilitando botones.
            btn_sort_mode.setAttribute('disabled', '');
            btn_apply_sort.setAttribute('disabled', '');
        }
    }

    //Deshabilitando capacidad de ordenamiento.
    allow_sort(false);

    //Obteniendo árbol ordenado.
    const ordered_tree = [];
    Array.from(root_list.children).forEach(function (item_li) {
        const attr = item_li.getAttribute(ATTR_DATA_SORT);
        if (attr.startsWith('G-'))
            ordered_tree.push({[attr]: Sortable.get($('.list-group', item_li)[0]).toArray()});
        else
            ordered_tree.push(attr);
    });

    ajax('POST', location.href + '/sort', {
        'sort': JSON.stringify(ordered_tree)
    }, {
        load: function (ev) {
            allow_sort(true);

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    //Saliendo del modo de ordenamiento, ya que los cambios se aplicaron bien.
                    SORTABLE_OBJ._backup_sort.clear();
                    SORTABLE_OBJ.ToggleSorting();
                    bootstrap.Collapse.getInstance($('#sort-caps-toolbar')).hide();

                    //Mostrando mensaje de confirmación.
                    ShowSweetAlert('success', 'Orden aplicado correctamente');
                } else {
                    if (json.warning !== undefined)
                        ShowSweetAlert('warning', json.warning);
                    else if (json.error !== undefined)
                        ShowSweetAlert('error', json.error);
                }
            } else if (ev.target.status === 403)
                ShowSweetAlert('error', 'No tiene privilegios suficientes');
            else
                ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
        }, error: function () {
            allow_sort(true);
            ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
        }
    });
});

//Events Create Group.
//Constantes de elementos.
const form_group = $('#group-form');
const modal_group = new bootstrap.Modal($('#modal-group'));

modal_group._element.addEventListener('hide.bs.modal', function () {
    //Eliminando atributo del formulario para deshabilitar la edición.
    form_group.removeAttribute(ATTR_DATA_GROUP);

    //Limpiando formulario.
    form_group.reset();
})

form_group.addEventListener('submit', function (ev) {
    ev.preventDefault();
    const form = this;
    const edit_mode = form.hasAttribute(ATTR_DATA_GROUP);
    const group = edit_mode ? form.getAttribute(ATTR_DATA_GROUP) : '';
    const title = $('#group-title').value.trim();

    const btn_submit = $('input[type="submit"]', this)[0];
    btn_submit.setAttribute('disabled', '');

    ajax('POST', location.href + '/group', {
        'title': title,
        'group': group.replace('G-', '')
    }, {
        load: function (ev) {
            btn_submit.removeAttribute('disabled');

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    const new_title = $('#group-title').value;
                    bootstrap.Modal.getInstance($('#modal-group')).hide();

                    //Si no es una edición, crear el nuevo elemento.
                    if (!edit_mode) CreateGroupElement(title, json.sort);
                    else {
                        const element = GetSelectedGroup(group);
                        $('.group-title', element)[0].textContent = new_title;
                    }

                    //Mostrando mensaje de confirmación.
                    ShowSweetAlert('success', 'Guardado correctamente');
                } else {
                    if (json.warning !== undefined)
                        ShowSweetAlert('warning', json.warning);
                    else if (json.error !== undefined)
                        ShowSweetAlert('error', json.error);
                }
            } else if (ev.target.status === 403)
                ShowSweetAlert('error', 'No tiene privilegios suficientes');
            else
                ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
        }, error: function () {
            btn_submit.removeAttribute('disabled');
            ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
        }
    });
});

function GetSelectedGroup(group){
    return  $(`#simpleList li[${ATTR_DATA_SORT}="${group}"]`)[0].firstElementChild;
}

const ATTR_DATA_GROUP = 'data-group';
const ATTR_DATA_SORT = 'data-sort-id';

function CreateGroupElement(title, sort_id) {
    const element = document.createElement('li');
    element.classList.add('sortable-group', 'p-0');
    element.setAttribute(ATTR_DATA_SORT, sort_id)
    element.innerHTML = `
                        <div class="list-group-item d-flex flex-grow-1 align-items-center">
                            <span class="fas fa-grip-lines-vertical align-self-center text-muted"></span>

                            <div class="d-flex flex-wrap flex-grow-1">
                                <img src="/files/img/basic/novel-cover.png?h=70" alt="Portada"
                                     class="wid-50 align-top m-r-15">

                                <div class="mt-auto mb-auto me-auto">
                                    <h6 class="group-title fw-bold m-0">${title}</h6>
                                    <button class="btn-edit-group btn btn-sm ps-0 pb-0">
                                        <i class="fas fa-edit fa-sm"></i> <u>Editar</u>
                                    </button>
                                </div>

                                <div class="d-flex flex-column flex-grow-0 justify-content-end ms-auto">
                                </div>
                            </div>
                        </div>
                        <ul id="sortable-list-${SORTABLE_OBJ.AllLists().length + 1}" class="list-group">
                        </ul>`;

    $('#simpleList > .list-group')[0].prepend(element);

    //Inicializando características del elemento.
    const element_header = element.firstElementChild;
    const element_body = element.lastElementChild;

    InitializeCollapseGroup(element_header, element_body, true)
    SORTABLE_OBJ.newSortableSubList(element_body);

    //Controlando botón editar del elemento.
    const btn_edit = $('.btn-edit-group', element_header)[0];
    btn_edit.addEventListener('click', () => EditGroupBtn_OnClick(btn_edit, element));
}

function EditGroupBtn_OnClick(btn, li_attr) {
    const form = $('#group-form');

    //Mostrando modal.
    modal_group.show();

    //Añadiendo atributo a formulario para habilitar la edición.
    const attr_sort_value = li_attr.getAttribute(ATTR_DATA_SORT);
    form.setAttribute(ATTR_DATA_GROUP, attr_sort_value);

    //Rellenando campos importantes.
    $('#group-title').value = btn.previousElementSibling.textContent;

    //Rellenando portada del grupo.
    const url = new URL(btn.parentElement.previousElementSibling.src);
    url.search = 'h=166';
    $('.uppy-Root img', form)[0].src = url.toString();
}