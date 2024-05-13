$('#form-personal-information').addEventListener('submit', function (ev) {
    ev.preventDefault();

    const info = $('.form-info', this)[0];
    const form_data = new FormData(this);

    //Validando datos.

    //Realizando petición.
    load(true, this);
    ajax('POST', '/user/settings/general', form_data, callbacks(this, function (info_box) {
        const first_name = form_data.get('fname').split(' ');
        const last_name = form_data.get('lname').split(' ');
        const shortname = (first_name.length > 0 ? first_name[0] : '') + " " + (last_name.length > 0 ? last_name[0] : '');
        $('.account-user-shortname').forEach(user_name => user_name.textContent = shortname);
        info_box.textContent = 'Información actualizada correctamente.';
    }));
});

$('#form-change-password').addEventListener('submit', function (ev) {
    ev.preventDefault();

    const info = $('.form-info', this)[0];
    const form = this;
    const form_data = new FormData(this);

    //Validando datos.
    if (form_data.get('pass') === form_data.get('new-pass')) {
        SetFormStatusInfo(info, 'La nueva contraseña no puede ser igual a la actual.', 'warning');
        return;
    } else if (form_data.get('new-pass') !== form_data.get('re-pass')) {
        SetFormStatusInfo(info, 'Las nuevas contraseñas especificadas no coinciden entre si.', 'warning');
        return;
    }

    //Realizando petición.
    load(true, this);
    ajax('POST', '/user/settings/password', form_data, callbacks(this, function (info_box) {
        form.reset();
        info_box.textContent = 'Contraseña actualizada correctamente.';
    }));
});

function callbacks(form, onSuccess) {
    const info_box = $('.form-info', form)[0];

    return {
        load: function (ev) {
            //Si expiró la sesión redireccionar.
            if (new URL(ev.target.responseURL).pathname === '/login') {
                SetFormStatusInfo(info_box, 'Sesión expirada, redireccionando...', 'error');
                location.href = '/login';
                return;
            }

            //Activando botones, etc.
            load(false, form);

            if (ev.target.status === 200) {
                const json = JSON.parse(ev.target.responseText);

                if (json[0] || json.success) {
                    info_box.classList.add('bg-success-color');
                    onSuccess(info_box);
                } else {
                    if (json.warning !== undefined) {
                        info_box.classList.add('bg-warning-color');
                        info_box.textContent = json.warning;
                    } else {
                        info_box.classList.add('bg-error-color');
                        info_box.textContent = 'Datos incorrectos, intente de nuevo.';
                    }
                }
            } else {
                info_box.classList.add('bg-error-color');
                info_box.textContent = 'Ha ocurrido un error, intente de nuevo.';
            }
        }, error: function () {
            load(false, form);
            info_box.classList.add('bg-error-color');
            info_box.textContent = 'Error de conexión.';
        }
    };
}

function load(enable, form) {
    const submit = $('input[type="submit"]', form)[0];
    const clear = $('input[type="reset"]', form)[0];
    const info = $('.form-info', form)[0];

    if (enable) {
        SetFormStatusInfo(info, '<i class="spinner-border"></i> Cargando...', 'load');
        submit.setAttribute('disabled', '');
        clear.setAttribute('disabled', '');
    } else {
        info.classList.remove('bg-load-color', 'bg-warning-color', 'bg-error-color', 'bg-success-color');
        submit.removeAttribute('disabled');
        clear.removeAttribute('disabled');
    }
}

function SetFormStatusInfo(info, label, status) {
    info.classList.remove('bg-load-color', 'bg-warning-color', 'bg-error-color', 'bg-success-color');
    info.classList.add('bg-' + status + '-color');
    info.innerHTML = label;
}