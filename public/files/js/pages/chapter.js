$('.btn-font-minus')[0].addEventListener('click', function () {
    ChangeFontSize(-2);
});

$('.btn-font-plus')[0].addEventListener('click', function () {
    ChangeFontSize(2);
});

function ChangeFontSize(value) {
    const reader_box = $('#novel-reader .content')[0];

    _ChangeFontSize(reader_box, value);
    ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].forEach(element => {
        $(element, reader_box).forEach(item => _ChangeFontSize(item, value));
    })
}

function _ChangeFontSize(element, value) {
    const styles = window.getComputedStyle(element, null);
    let result = parseInt(styles.getPropertyValue('font-size')) + value;
    if (result <= 4) result = 4;
    element.style.fontSize = result + 'px';
}