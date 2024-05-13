'use strict';

const ATTR_PRESSED = 'pressed';
const ATTR_OPEN = 'open';
const ATTR_BACKUP_REF = 'data-href';
const ATTR_SHOW = 'show';

window.addEventListener('load', function () {
    init_box();
    init_tabs();
    init_buttons();
    init_lists();
    resize_lists();

    init_topbar();

    //textarea
    $('textarea.auto-resize').forEach(item => item.addEventListener('keyup', x => {
        x.currentTarget.style.height = (x.currentTarget.scrollHeight + 4) + "px";
    }));
});

window.addEventListener('resize', resize_lists);

function resize_lists() {
    if (window.innerWidth < 750) {
        let sub_lists = $('.list .item[href] + ul');

        sub_lists.forEach(function (sublist) {
            const item = $('.item', sublist.parentElement)[0];
            item.removeAttribute('href');
        })
    } else {
        let sub_lists = $('.list .item:not([href]) + ul');

        sub_lists.forEach(function (sublist) {
            const item = $('.item', sublist.parentElement)[0];
            item.setAttribute('href', item.getAttribute('data-href'));
        })
    }
}

function ToggleAttr(obj, attr_name) {
    if (obj.getAttribute(attr_name) === null) obj.setAttribute(attr_name, ""); else obj.removeAttribute(attr_name);
}

function AddHoverEffect(item) {
    item.onmouseover = function () {
        this.classList.add("hover");
    }
    item.onmouseout = function () {
        this.classList.remove("hover");
    }
}

function AddFocusEffect(item) {
    if (!item.classList.contains('no-focus')) {
        item.onfocus = item.onclick = function () {
            item.tabIndex = 0;
            this.classList.add("focus");
            this.focus();
        }
        item.onblur = function () {
            this.classList.remove("focus");
        }
    }
}

function init_topbar() {
    const btn_notify = $('#top-menu .notification');

    $('#top-menu .menu')[0].addEventListener('click', function () {
        ToggleAttr($('#lateral-menu'), ATTR_OPEN);
    });

    $('#top-menu .search')[0].addEventListener('click', function () {
        const parent = $('#extra-menu .search-box')[0];
        ToggleAttr(parent, ATTR_OPEN);
        $('input', parent)[0].focus();
    });

    $('#top-menu .account')[0].addEventListener('click', function () {
        if (btn_notify.length === 1) $('#extra-menu .notification-box')[0].removeAttribute(ATTR_OPEN);
        ToggleAttr($('#extra-menu .account-box')[0], ATTR_OPEN);
    });

    if (btn_notify.length === 1) {
        btn_notify[0].addEventListener('click', function () {
            $('#extra-menu .account-box')[0].removeAttribute(ATTR_OPEN);
            ToggleAttr($('#extra-menu .notification-box')[0], ATTR_OPEN);
        });
    }
}

function init_buttons() {
    //Botones con iconos.
    $('.btn-icon').forEach(item => {
        if (item.onclick == null) item.addEventListener('click', () => ToggleAttr(item, ATTR_PRESSED));
    });
}

function init_lists() {
    $('.list .item').forEach(item => {
        //Agregando efectos.
        AddHoverEffect(item);
        AddFocusEffect(item);

        //Deteniendo propagación de los botones de las listas.
        $('button', item).forEach(btn => {
            btn.addEventListener('click', ev => ev.stopPropagation());
        });

        //Si es un elemento que contiene otra sublista.
        if (item.nextElementSibling?.nodeName === 'UL') {
            //Si es un enlace realizar backup de href.
            if (item.nodeName === 'A') {
                let url = window.location.origin;
                url = item.href.startsWith(url) ? item.href.substring(url.length) : item.href;
                item.setAttribute(ATTR_BACKUP_REF, url);
            }

            //Añadiendo capacidad de expandir el elemento.
            const container = item.parentElement;
            DoExpandContainer(container, container, item, ATTR_OPEN);
        }
    })
}

function init_tabs() {
    $('.tabs-header .tab').forEach(tab => {
        const tabs_header = $closest(tab, x => x.classList.contains('tabs-header'));

        tab.addEventListener('click', () => {
            //Ocultando y mostrando contenido de la pestaña.
            const tabs_box = tabs_header.parentElement.classList.contains('tabs') ? tabs_header.parentElement : null;
            const tab_name = tab.getAttribute('data-tabname');

            //Obteniendo tab content seleccionado.
            const tab_content = $('.tab-item[data-tabname="' + tab_name + '"]', tabs_box)[0];

            if (tab_content !== undefined) {
                //De-seleccionando y seleccionando pestaña.
                $('.tab[' + ATTR_PRESSED + ']', tabs_header)[0]?.removeAttribute(ATTR_PRESSED);
                tab.setAttribute(ATTR_PRESSED, '')

                //Ocultando demás tabs.
                $('.tab-item[' + ATTR_SHOW + ']', tab_content.parentElement)[0]?.removeAttribute(ATTR_SHOW);

                //Mostrando la seleccionada.
                tab_content.setAttribute(ATTR_SHOW, '');
            }
        });
    });

    /*var Tabs_box = document.getElementsByClassName('Tabs-box');

    ForEach(Tabs_box, function (Tab_box) {
        ForEach(Tab_box.children, function (tab) {
            tab.onclick = TabClick;
        });
    });*/
}

function init_box() {
    $('.box.can-min').forEach(box => {
        //Añadiendo capacidad de expandir el elemento.
        const icon = box.firstElementChild;
        DoExpandContainer(box, icon, icon, ATTR_OPEN);
    });
}

function DoExpandContainer(obj_open, obj_click, obj_icon, attr) {
    //Añadiendo icono de expansión.
    if (!obj_icon.classList.contains('no-auto-expand-icon'))
        obj_icon.innerHTML += `<i class='m-icons expand-icon'><i>`;
    AddIconExpand(obj_open, obj_icon);

    if (obj_icon.getAttribute(ATTR_DISABLED) == null) {
        obj_click.addEventListener('click', () => {
            ToggleAttr(obj_open, attr);
            AddIconExpand(obj_open, obj_icon);
        });
    }
}

function AddIconExpand(obj_open, obj_icon) {
    const attr = obj_open.getAttribute(ATTR_OPEN);
    const icons = $(".expand-icon", obj_icon)

    if (icons.length > 0) {
        const icon = icons[icons.length - 1];

        /*if (revert) {
            if (attr == null) attr = "";
            else attr = null;
        }*/

        icon.textContent = (attr == null) ? "expand_more" : "expand_less";
    }
}


///////////////////////////////////////////////////////////////////////


