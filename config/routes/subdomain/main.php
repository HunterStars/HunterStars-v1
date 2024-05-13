<?php

namespace HS\config\routes;

use HS\app\models\items\EntryItem;
use HS\app\models\items\ProjectItem;
use HS\config\enums\AppRegex;
use HS\config\enums\SubDomains;
use HS\libs\core\Route;
use HS\libs\core\RouteItem;
use HS\libs\core\Session;
use HS\libs\helpers\MimeType;
use HS\libs\helpers\Regex;
use HS\libs\net\HttpMethod;

if (in_array($_SERVER['SERVER_NAME'], [SubDomains::root->value, SubDomains::www->value])) {
    #Pagina de inicio.
    Route::Get('/', 'NovelsController#Index');


    #Novelas.
    Route::Get('/novels', 'NovelsController#Index');
    Route::Section('/novels/{project}', [
        new RouteItem(HttpMethod::GET, '', 'NovelsController#Item', ['project' => ProjectItem::REGEX_TITLE]),
        new RouteItem(HttpMethod::POST, '/comment', 'NovelsController#AddComment', [], [
            'c-text' => '.+',
            '¿code?' => '.+'
        ], MimeType::Json),
        new RouteItem(HttpMethod::POST, '/{chapter}/comment', 'NovelsController#AddComment', [], [
            'c-text' => '.+',
            '¿code?' => '.+'
        ], MimeType::Json),
        new RouteItem(HttpMethod::GET, '/download', 'NovelsController#DownloadAll', ['project' => ProjectItem::REGEX_TITLE], [], MimeType::PDF),
        new RouteItem(HttpMethod::GET, '/download/{group}.pdf', 'NovelsController#DownloadGroup', ['project' => ProjectItem::REGEX_TITLE, 'group' => Regex::UNSIGNED_INT], [], MimeType::PDF),
        new RouteItem(HttpMethod::GET, '/{chapter}', 'NovelsController#Chapter', ['project' => ProjectItem::REGEX_TITLE, 'chapter' => EntryItem::REGEX_NAME])
    ]);

    #A partir de aquí para todas las rutas es obligatorio que la sesión esté iniciada.
    Session::IfNoLoginRedirect();

    #Usuarios.
    Route::Get('/user/settings', 'AccountController#Index');
    Route::Post('/user/settings/general', 'AccountController#ActionChangeGeneralInformation', [], [
        'fname' => '.+',
        'lname' => '.+',
        'uname' => '.+',
        'email' => '.*'
    ], MimeType::Json);
    Route::Post('/user/settings/password', 'AccountController#ActionChangePassword', [], [
        'pass' => '.+',
        'new-pass' => '.+',
        're-pass' => AppRegex::UserPass
    ], MimeType::Json);

    Route::Get('/user/favorites', 'AccountController#ViewFavorites');
    Route::Post('/user/favorites/add', 'AccountController#ActionAddFavorite', [], ['novel' => ProjectItem::REGEX_URL], MimeType::Json);
    Route::Post('/user/favorites/remove', 'AccountController#ActionRemoveFavorite', [], ['novel' => ProjectItem::REGEX_URL], MimeType::Json);
}