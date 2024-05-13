/Constantes.
const ATTR_DISABLED = 'disabled';

function $(element, parent = null) {
    const item = (parent ?? document);

    if (/^[.#]?[^#. :>\[~]+$/.test(element)) {
        if (element.startsWith("#")) return item.getElementById(element.substring(1)); else if (element.startsWith('.')) return Array.from(item.getElementsByClassName(element.substring(1))); else return Array.from(item.getElementsByTagName(element));
    } else return item.querySelectorAll(element);
}

function $closest(el, fn) {
    return el && el !== document.body && (fn(el) ? el : $closest(el.parentNode, fn));
}

function $isInViewport(elem) {
    const distance = elem.getBoundingClientRect();
    return (
        distance.bottom < (window.innerHeight || document.documentElement.clientHeight) && distance.bottom > 0
    );
}

/*
const $on = (element, type, selector, handler) => {
    element.addEventListener(type, (event) => {
        if (event.target.closest(selector)) {
            handler(event);
        }
        console.log(event.target);
    });
};*/

if (JSON) {
    JSON.tryParse = function (text) {
        try {
            return JSON.parse(text);
        } catch {
            return undefined;
        }
    }
}

function ajax(method, url, params, callbacks) {
    //Creando objeto de parámetros de formulario.
    let form_data = params;
    if (!(params instanceof FormData)) {
        form_data = new FormData();
        for (let key in params) form_data.append(key, params[key]);
    }

    //Creando objeto request.
    const request = new XMLHttpRequest();

    //Añadiendo controladores de eventos.
    for (let type in callbacks) {
        if (type.startsWith('upload')) request.upload.addEventListener(type.substring(7), callbacks[type]); else request.addEventListener(type, callbacks[type]);
    }

    //Si el método es GET y hay parámetros modificar url.
    if (method.toUpperCase() === 'GET' && Object.entries(params).length > 0) {
        params = Object.entries(params).map(([k, v], i) => k + '=' + encodeURIComponent(v));
        url += '?' + params.join('&');
    }

    //Abriendo y enviando request.
    request.open(method, url, true);
    request.send(form_data);

    return request;
}

function DoRequest(method, url, data, OnSuccess, OnWarning, OnError, OnNativeError, OnLoginRequired) {
    ajax(method, url, data, {
        load: function (ev) {
            if (ev.target.status === 200) {
                //Si expiró la sesión redireccionar.
                if (new URL(ev.target.responseURL).pathname === '/login') {
                    if (!OnLoginRequired) location.reload(); else if (OnLoginRequired() !== false) return;
                }

                const json = JSON.tryParse(ev.target.responseText);

                if (json !== undefined) {
                    if (json.success) {
                        if (OnSuccess) OnSuccess(json);
                    } else if (json.warning) {
                        if (OnWarning) OnWarning(json);
                    } else if (json.error) {
                        if (OnError) OnError(json);
                    }
                } else {
                    if (OnNativeError) OnNativeError("El formato de la respuesta es incorrecto.");
                }
            } else {
                if (OnNativeError) OnNativeError("Ha ocurrido un error al procesar la solicitud.");
            }
        }, error: function () {
            if (OnNativeError) OnNativeError("Ha ocurrido un error al establecer conexión.");
        }
    });
}

function DoFormRequest(form, OnSuccess, OnWarning, OnError, isLoginRequired) {
    const action = form.getAttribute('action');

    DoRequest('POST', action, new FormData(form), OnSuccess, OnWarning, OnError, () => isLoginRequired);
}

//Propios
function DoFormInfoBoxRequest(form, OnSuccess, OnWarning, OnError, isLogin = false) {
    const info_box = $('.form-info')[0];
    const action = form.getAttribute('action');

    DoRequest('POST', action, new FormData(form), function (json) {
        if (OnSuccess) OnSuccess(json, info_box);
    }, function (json) {
        formInfoBox(info_box, json.warning, 'warning');
        if (OnWarning) OnWarning(json, info_box);
    }, function (json) {
        formInfoBox(info_box, 'Datos incorrectos, intente de nuevo.', 'error');
        if (OnError) OnError(json, info_box);
    }, function (error) {
        formInfoBox(info_box, error + ' Intente de nuevo.', 'error');
        if (OnError !== null) OnError(undefined, info_box);
    }, () => !isLogin);
}

function formInfoBox(info, label, status, load_spinner = false) {
    info.classList.remove('bg-load-color', 'bg-warning-color', 'bg-error-color', 'bg-success-color');
    info.classList.add('bg-' + status + '-color');
    if (load_spinner) info.innerHTML = '<i class="spinner-border"></i> ' + label; else info.textContent = label;
}

/*function ObjectMap(obj, fn) {
    return Object.fromEntries(
        Object.entries(obj).map(
            ([k, v], i) => [k, fn(v, k, i)]
        )
    )
}*/