<?php

namespace HS\config\routes;


use HS\config\enums\AppImageDirs;
use HS\config\enums\SubDomains;
use HS\libs\core\Route;
use HS\libs\helpers\Regex;
use HS\libs\media\ImageFormat;

Route::Get('/files/img/{type+}/{filename}', 'ImageController#Get', [
    'type' => AppImageDirs::ToCasesArray(),
    'filename' => fn($file) => in_array(ImageFormat::tryFrom(pathinfo($file, PATHINFO_EXTENSION)), ImageFormat::cases())
], ['¿h?' => '^\d+$', '¿w?' => '^\d+$']);

$filename_condition = fn($file) => in_array(ImageFormat::tryFrom(pathinfo($file, PATHINFO_EXTENSION)), ImageFormat::cases());
$group_post_condition = ['¿h?' => Regex::UNSIGNED_INT, '¿w?' => Regex::UNSIGNED_INT];

switch ($_SERVER['SERVER_NAME']) {
    case SubDomains::root->value:
    case SubDomains::www->value:
        Route::Get('/novels/{project}/cover/{filename}', 'ImageController#GetProject',
            ['filename' => $filename_condition], $group_post_condition);
        Route::Get('/novels/{project}/img/{filename}', 'ImageController#GetChapter',
            ['filename' => $filename_condition], $group_post_condition);
        break;
    case SubDomains::studio->value:
        //Imágenes de proyectos y Grupos.
        Route::Get('/{circle}/cover/{filename}', 'ImageController#GetAdminCircle',
            ['filename' => $filename_condition], $group_post_condition);
        Route::Get('/{circle}/{project}/cover/{filename}', 'ImageController#GetAdminProject',
            ['filename' => $filename_condition], $group_post_condition);
        Route::Get('/{circle}/{project}/img/{filename}', 'ImageController#GetAdminProject',
            ['filename' => $filename_condition], $group_post_condition);
        break;
}
