function getNovelUrl() {
    let url = self.location.pathname;
    url = url.endsWith('/') ? url.substring(0, url.length - 1) : url;
    return url.substring(url.lastIndexOf('/') + 1, url.length);
}

function addFavorite(btn) {
    DoRequest('POST', '/user/favorites/add', {
        novel: getNovelUrl().trim()
    }, function () {
        $('.m-icons', btn)[0].textContent = 'favorite';
        $('span', btn)[0].textContent = 'Favorito';
    }, null, function () {
        console.log("No fue posible agregar a favoritos.");
    })
}

function removeFavorite(btn) {
    DoRequest('POST', '/user/favorites/remove', {
        novel: getNovelUrl().trim()
    }, function () {
        $('.m-icons', btn)[0].textContent = 'favorite_border';
        $('span', btn)[0].textContent = 'Agregar a favoritos';
    }, null, function () {
        console.log("No fue posible quitar esto de sus favoritos.");
    })
}

$('#btn-favorite').addEventListener('click', function () {
    if ($('.m-icons', this)[0].textContent.includes('border')) addFavorite(this); else removeFavorite(this);
});