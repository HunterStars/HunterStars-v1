const ATTR_CIRCLE_TYPE = 'data-circle-type';

$('.nav-pills .nav-link').forEach(function (tab) {
    tab.addEventListener('click', function () {
        const tab_type = tab.getAttribute(ATTR_CIRCLE_TYPE);
        let visible_count = 0;

        $('.user-card').forEach(function (card) {
            if (tab_type === null || card.getAttribute(ATTR_CIRCLE_TYPE) === tab_type) {
                card.style.display = 'flex';
                visible_count++;
            } else card.style.display = 'none';
        });

        $('#warning-create-circle').style.display = visible_count === 0 ? 'block' : 'none';
    });
});

$('#circle-form').addEventListener('submit', function (ev) {
    ev.preventDefault();

    const form = this;

    EnableForm(this, false);

    DoStudioRequest(this, null, function (json) {
        //Cerrando modal.
        bootstrap.Modal.getInstance($('#modal-circle')).hide()

        //Limpiando datos del formulario.
        form.reset();

        //Mostrando mensaje de confirmación.
        ShowSweetAlert('success', 'Creado correctamente, redireccionando...');

        //Redireccionando para continuar la edición.
        window.location = json.url;
    }, function (json) {
        switch (json.code) {
            case 2:
            case 6:
            case 7:
                $('#circle-url').focus();
                break;
        }
    })
});