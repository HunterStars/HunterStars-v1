$('#novel-form').addEventListener('submit', function (ev) {
    ev.preventDefault();

    const novel_url = $('#novel-url');

    //Bloqueando botones.
    const btn_submit = $('input[type="submit"]', this)[0];
    const btn_reset = $('input[type="reset"]', this)[0];
    const enable_button = function (enable) {
        if (!enable) {
            btn_submit.setAttribute('disabled', '');
            btn_reset.setAttribute('disabled', '');
        } else {
            btn_submit.removeAttribute('disabled');
            btn_reset.removeAttribute('disabled');
        }
    }
    enable_button(false);

    ajax('POST', location.href, {
        'title': $('#novel-title').value,
        'title_alt': $('#novel-title-alt').value,
        'url': novel_url.value,
        'state': $('#novel-state').value
    }, {
        load: function (ev) {
            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    //Cerrando modal.
                    bootstrap.Modal.getInstance($('#modal-report')).hide()

                    //Limpiando datos del formulario.
                    $('#novel-form').reset();

                    //Mostrando mensaje de confirmación.
                    ShowSweetAlert('success', 'Guardado correctamente');

                    //Redireccionando para continuar la edición.
                    window.location = './' + json.url;
                } else {
                    enable_button(true);

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
            } else {
                enable_button(true);
                ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
            }
        }, error: function () {
            enable_button(true);
            ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
        }
    });
});