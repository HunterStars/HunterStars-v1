const BTN_SAVE_CHAPTER = $('#save-form button[type="submit"]')[0];
let BTN_SAVE_CLICK = false;

const watchdog = new CKSource.EditorWatchdog(CKSource.Editor);
watchdog.create(document.querySelector('.document-editor__editable'), {
    title: {placeholder: 'Escribe el titulo aquí'},
    placeholder: '¡Escribe el contenido aquí!',
    language: {
        ui: 'es',
        content: 'es',
        textPartLanguage: [
            {title: "Español", languageCode: "es"},
            {title: "English", languageCode: "en"}
        ]
    }, list: {
        properties: {
            styles: true, startIndex: true, reversed: true
        }
    }, image: {
        toolbar: ["imageStyle:inline", "imageStyle:wrapText", "imageStyle:breakText", "|", 'resizeImage:original',
            'resizeImage:50', "|", "toggleImageCaption", "imageTextAlternative", '|', 'linkImage']
    }, link: {
        defaultProtocol: 'https://', // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
        //addTargetToExternalLinks: true,
        decorators: [{
            mode: 'automatic',
            callback: url => /^(https?:)?\/\//.test(url),
            attributes: {
                target: '_blank',
                rel: 'noopener noreferrer nofollow'
            }
        },
            {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        ]
    }, wordCount: {
        onUpdate: stats => {
            const box = $('#word-count');
            box.firstElementChild.textContent = `Palabras: ${stats.words}`;
            box.lastElementChild.textContent = `Caracteres: ${stats.characters}`;
        }
    }, simpleUpload: {
        uploadUrl: 'img',

        // Enable the XMLHttpRequest.withCredentials property.
        /*withCredentials: true,

        // Headers sent along with the XMLHttpRequest to the upload server.
        headers: {
            'X-CSRF-TOKEN': 'CSRF-Token',
            Authorization: 'Bearer <JSON Web Token>'
        }*/
    },
    autosave: {
        waitingTime: 5000, // in ms
        save() {
            return AutoSave();
        }
    }
}).then(() => {
    document.querySelector('.document-editor__toolbar').appendChild(watchdog.editor.ui.view.toolbar.element);
    watchdog.editor.model.document.on('change:data', OnDataChanges);
    window.editor = watchdog.editor;
}).catch(err => {
    console.error(err);
});

//Save Events
function OnDataChanges() {
    //Habilitando botón de guardado cuando existan cambios.
    if (watchdog.editor.plugins.get('Autosave').state !== 'saving') {
        //Estableciendo estado en el botón de guardado.
        BTN_SAVE_CHAPTER.lastElementChild.textContent = "Guardar";
        EnableSaveButton(true);
    }
}

function AutoSave() {
    const date = new Date();
    const status_label = $('#save-status');
    const status_label_backup = status_label.textContent;
    const backup_data = watchdog.editor.getData();
    const btn_save_label = BTN_SAVE_CHAPTER.lastElementChild;
    const btn_icon_classes = BTN_SAVE_CHAPTER.firstElementChild.classList;

    const LoadAnimationOnSaveButton = (enable) => {
        if (enable) {
            btn_icon_classes.remove('fas');
            btn_icon_classes.remove('fa-save');
            btn_icon_classes.add('spinner-border');
            btn_icon_classes.add('spinner-border-sm');
        } else {
            btn_icon_classes.remove('spinner-border');
            btn_icon_classes.remove('spinner-border-sm');
            btn_icon_classes.add('fas');
            btn_icon_classes.add('fa-save');
        }
    };

    //Estableciendo estado en el botón de guardado.
    btn_save_label.textContent = "Guardando...";
    EnableSaveButton(false);
    LoadAnimationOnSaveButton(true);

    //Estableciendo estado en caso de guardado automático.
    if (!BTN_SAVE_CLICK)
        status_label.textContent = 'Guardado automático en proceso...';

    return SaveChapter().then(function (url) {
        LoadAnimationOnSaveButton(false);

        //Mostrando mensaje de confirmación.
        if (BTN_SAVE_CLICK)
            ShowSweetAlert('success', 'Guardado correctamente');
        //else ShowSweetAlert('success', 'Guardado automático terminado.');

        BTN_SAVE_CLICK = false;

        //Si no hubo cambios mientras se guardaba.
        if (backup_data === watchdog.editor.getData() && url !== null) {
            //Estableciendo estado en el botón de guardado.
            btn_save_label.textContent = "Guardado";
        } else {
            //Si hubo cambios habilitar el botón de guardado.
            btn_save_label.textContent = "Guardar";
            EnableSaveButton(true);
        }

        //Estableciendo hora de modificación.
        status_label.textContent = 'Modificado el ' + date.toLocaleString();
    }).catch(function () {
        //Si hubo error, habilitar nuevamente el botón de guardado.
        LoadAnimationOnSaveButton(false);
        btn_save_label.textContent = "Guardar";
        EnableSaveButton(true);

        //Reestableciendo ultima hora de modificación.
        status_label.textContent = status_label_backup;
    });
}

$('#save-form').addEventListener('submit', function (ev) {
    ev.preventDefault();
    BTN_SAVE_CLICK = true;
    watchdog.editor.plugins.get('Autosave').save();
});

function SaveChapter() {
    return XHRRequestForSaveChapter().then(function (url) {
        //Reemplazando URL actual.
        if (url !== undefined)
            history.replaceState(null, '', url);
    });
}

function XHRRequestForSaveChapter() {
    const ckeditor_plugin = watchdog.editor.plugins.get("Title");

    return new Promise(function (resolve, reject) {
        ajax('POST', location.href, {
            'title': ckeditor_plugin.getTitle(),
            'content': ckeditor_plugin.getBody()
        }, {
            load: function (ev) {
                //Si expiró la sesión redireccionar.
                if (ev.target.responseURL !== location.href)
                    location.href = '/login';

                if (ev.target.status === 200) {
                    const json = JSON.parse(ev.target.responseText);

                    if (json.success) {
                        resolve(json.url);
                    } else {
                        if (json.warning !== undefined) {
                            ShowSweetAlert('warning', json.warning);
                        } else if (json.error !== undefined)
                            ShowSweetAlert('error', json.error);

                        reject();
                    }
                } else if (ev.target.status === 403) {
                    ShowSweetAlert('error', 'No tiene privilegios suficientes');
                    reject();
                } else {
                    ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
                    reject();
                }

            }, error: function () {
                ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
                reject();
            }
        });
    });
}

function EnableSaveButton(enable) {
    if (enable)
        BTN_SAVE_CHAPTER.removeAttribute('disabled');
    else
        BTN_SAVE_CHAPTER.setAttribute('disabled', '');
}