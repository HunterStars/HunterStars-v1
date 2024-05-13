ShowMenuOptionNameInBar()

function ShowMenuOptionNameInBar() {
    let selected_item = $('.pc-navbar .pc-item.active .pc-mtext');
    if (selected_item.length > 0) {
        selected_item = selected_item[0];
        $('#site-path').innerHTML += `<li class="breadcrumb-item">
                <a href="${selected_item.parentElement.href}">${selected_item.textContent}</a>
            </li>`;
    }
}

function ShowSweetAlert(type, title) {
    Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    }).fire({
        icon: type, title: title
    })
}

function DoStudioRequest(form, data, OnSuccess, OnWarning, OnError) {
    const action = form.getAttribute('action');

    ajax('POST', action, data === null ? new FormData(form) : data, {
        load: function (ev) {
            if (ev.target.status === 200) {
                //Si expiró la sesión redireccionar.
                if (new URL(ev.target.responseURL).pathname === '/login') {
                    ShowSweetAlert('warning', 'Sesión finalizada, redireccionando...');
                    location.reload();
                    return;
                }

                const json = JSON.parse(ev.target.responseText);

                if (json.success) {
                    if (OnSuccess) OnSuccess(json); else {
                        ShowSweetAlert('success', "Acción realizada correctamente.");
                    }
                    return;
                } else if (json.warning) {
                    if (OnWarning) OnWarning(json);

                    ShowSweetAlert('warning', json.warning);
                } else if (json.error) {
                    if (OnError) OnError(json);

                    ShowSweetAlert('error', json.error)
                } else {
                    ShowSweetAlert('error', "Respuesta no valida");
                }

                EnableForm(form, true);
            } else if (ev.target.status === 403) {
                EnableForm(form, true);
                ShowSweetAlert('error', 'No tiene suficientes privilegios para realizar esta solicitud.');
            } else {
                EnableForm(form, true);
                ShowSweetAlert('error', 'Ha ocurrido un error al procesar su solicitud.');
            }
        }, error: function () {
            EnableForm(form, true);
            ShowSweetAlert('error', 'Error al establecer conexión con el servidor.');
        }
    });
}

function EnableForm(form, enable) {
    //Obteniendo botones y spinners.
    let btn_submit = $('button[type="submit"]', form)[0];
    let btn_reset = $('button[type="reset"]', form)[0];

    if (btn_submit === undefined) btn_submit = $('input[type="submit"]', form)[0];
    if (btn_reset === undefined) btn_reset = $('input[type="reset"]', form)[0];

    const spinner = $('.spinner-border', btn_submit)[0];

    //Deshabilitando botones.
    if (enable) {
        spinner.classList.add('d-none');
        btn_submit.removeAttribute(ATTR_DISABLED);
        btn_reset?.removeAttribute(ATTR_DISABLED);
    } else {
        spinner?.classList.remove('d-none');
        btn_submit.setAttribute(ATTR_DISABLED, '');
        btn_reset?.setAttribute(ATTR_DISABLED, '');
    }
}