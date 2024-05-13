$('#login-form')?.addEventListener('submit', function (ev) {
    ev.preventDefault();

    load(true, 'Iniciando sesión...');

    DoFormInfoBoxRequest(this, function (json, info) {
        formInfoBox(info, 'Iniciando sesión...', 'success', true);
        window.location = json.returnUrl;
    }, () => load(false), () => load(false), true);
});

$('#register-form')?.addEventListener('submit', function (ev) {
    ev.preventDefault();

    load(true, 'Registrando...');

    DoFormInfoBoxRequest(this, function (json, info) {
        formInfoBox(info, 'Finalizando registro...', 'success', true);
        window.location = '/login?user=' + encodeURIComponent(json.user);
    }, () => load(false), () => load(false));
});

let backup_btn_label;

function load(enable, load_label = null) {
    const spinner = $('.spinner-border')[0];
    const submit = $('button[type="submit"]')[0];
    const submit_label = $('span', submit)[0];

    if (enable) {
        backup_btn_label = submit_label.textContent;
        spinner.style.display = 'block';
        submit_label.textContent = load_label;
        submit.setAttribute('disabled', '');
    } else {
        spinner.style.display = 'none';
        submit_label.textContent = backup_btn_label;
        submit.removeAttribute('disabled');
    }
}