<?php

namespace HS\config\routes;

use HS\config\enums\AppRegex;
use HS\libs\core\Route;
use HS\libs\helpers\MimeType;

#Login Routes
Route::Get("/login", "LoginController#Index", [], ['¿user?' => AppRegex::UserNick]);
Route::Post('/login', 'LoginController#ActionLogin', [], ['user-name' => AppRegex::UserNick, 'user-pass' => AppRegex::UserPass], MimeType::Json);
Route::Get('/logout', 'LoginController#Logout');

Route::Get("/register", "RegisterController#Index");
Route::Post("/register", "RegisterController#Action", [], [
    'fname' => '.+',
    'lname' => '.+',
    'user' => '.+',
    'email' => '.*',
    'pass' => '.+',
    'repass' => '.+'
], MimeType::Json);

#SCSS Routes.
Route::Get('/files/css/{filename+}.css', 'SCSSController#Get');
Route::Get('/files/css/{filename+}.css.map', 'SCSSController#GetMap');
Route::Get('/files/{type}/scss/{filename+}.scss', 'SCSSController#GetSCSS', [
    'type' => ['client', 'admin', 'common']
]);
