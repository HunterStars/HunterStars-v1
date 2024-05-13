function GetUppyModalInstance(id, aspect_ratio, xhr_target, metadata_fields = []) {
    const uppy_box = document.getElementById(id);
    const uppy = new Uppy.Uppy({
        autoProceed: false, allowMultipleUploadBatches: false, restrictions: {
            maxNumberOfFiles: 1, allowedFileTypes: ['image/*']
        }, locale: Uppy.locales.es_ES
    });

    uppy.use(Uppy.Dashboard, {
        target: '#' + uppy_box.id,
        trigger: $('.uppy-btn-show-modal', uppy_box.parentElement)[0],
        inline: false,
        theme: 'dark',
        showProgressDetails: true,
        note: 'Seleccione la Nueva Imagen',
        autoOpenFileEditor: true,
        closeModalOnClickOutside: true,
        closeAfterFinish: true,
        /*metaFields: [
            {id: 'caption', name: 'Descripción', placeholder: 'Añade una descripción'},
        ],*/
    });
    uppy.use(Uppy.ImageEditor, {
        target: Uppy.Dashboard, quality: 0.8, cropperOptions: {
            viewMode: 1,
            dragMode: 'move',
            aspectRatio: aspect_ratio,
            modal: false,
            guides: true,
            cropBoxResizable: true
        }, actions: {
            revert: false, cropSquare: false, cropWidescreen: false, cropWidescreenVertical: false
        }
    });
    uppy.use(Uppy.XHRUpload, {
        allowedMetaFields: [... metadata_fields, 'type'],
        endpoint: xhr_target,
        method: 'post'
    });
    uppy.on('file-editor:complete', (file) => uppy.upload());
    uppy.on('dashboard:modal-closed', () => uppy.cancelAll());
    uppy.on('upload-success', (file, response) => {
        const img = $('.uppy-Root .uppy-Dashboard-AddFiles-list img', uppy_box.parentElement)[0];
        img.src = URL.createObjectURL(file.data)
        img.classList.remove('d-none');
    });
    uppy_box.addEventListener('click', function (ev) {
        if (ev.target && ev.target.className === "uppy-DashboardContent-back") uppy.cancelAll();
    })

    return uppy;
}