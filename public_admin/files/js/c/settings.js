//Uppy Upload.
$('.uppy-files-drag-drop').forEach(function (uppy_box, index) {
    uppy_box.id = 'uppy-file-drag-drop-' + (index + 1);

    const uppy = GetUppyModalInstance(uppy_box.id, uppy_box.getAttribute('data-aspect-ratio'), uppy_box.getAttribute('data-target'));
});

//Choices en formulario.
new Choices($('#circle-type'), {
    searchEnabled: false, itemSelectText: 'Presiona para seleccionar', allowHTML: true
});

//Rich Text
const watchdog = new CKSource.EditorWatchdog(CKSource.Editor);
watchdog.create(document.querySelector('#desc-editor'), {
    toolbar: {
        shouldNotGroupWhenFull: false
    }, language: {
        ui: 'es',
        content: 'es',
        textPartLanguage: [{title: "Español", languageCode: "es"}, {title: "English", languageCode: "en"}]
    }, list: {
        properties: {
            styles: true, startIndex: true, reversed: true
        }
    }
});

$('#circle-form').addEventListener('submit', function (ev) {
    ev.preventDefault();

    EnableForm(this, false);

    //Formulario.
    const form = this;
    const form_data = new FormData(this);
    form_data.delete('files[]');
    form_data.set('desc', watchdog.editor.getData());

    DoStudioRequest(this, form_data, function (json) {
        //Reemplazando todas las url que apunten a esta dirección.
        const path_regex = /^(\/[^\/]+)(?:\/.+)?$/;
        $('a[href^="' + path_regex.exec(location.pathname)[1] + '"]').forEach(url => {
            const path_href = /^(\/[^\/]+)(\/.+)?$/.exec(new URL(url.href).pathname);
            url.href = path_regex.exec(json.url)[1] + (path_href?.length === 3 && path_href[2] !== undefined ? path_href[2] : '')
        });

        //Reemplazando URL actual.
        history.replaceState(null, '', json.url);

        //Mostrando mensaje de confirmación.
        ShowSweetAlert('success', 'Información actualizada.');

        //Habilitando botones.
        EnableForm(form, true);
    }, function (json) {
        switch (json.code) {
            case 2:
            case 6:
            case 7:
                $('#circle-url').focus();
                break;
        }
    });
});

