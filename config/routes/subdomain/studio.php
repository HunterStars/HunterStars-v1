<?php

namespace HS\config\routes;

use HS\app\models\items\CircleItem;
use HS\app\models\items\EntryItem;
use HS\app\models\items\GroupItem;
use HS\app\models\items\ProjectItem;
use HS\app\models\items\TagItem;
use HS\config\enums\SubDomains;
use HS\libs\core\Route;
use HS\libs\core\Session;
use HS\libs\helpers\MimeType;
use HS\libs\helpers\Regex;

if (SubDomains::studio->value === $_SERVER['SERVER_NAME']) {
    Session::IfNoLoginRedirect(true);

    #Circles.
    //Lista.
    Route::Get('/', 'CircleController#Index');

    //Creación y edición.
    Route::Post('/circle', 'CircleController#CreateEditAction', [], [
        'url' => CircleItem::REGEX_NAME,
        'title' => CircleItem::REGEX_TITLE,
        'type' => Regex::UNSIGNED_INT
    ], MimeType::Json);
    Route::Get('/{circle}/settings', 'CircleController#ViewEdit', ['circle' => CircleItem::REGEX_NAME]);
    Route::Post('/{circle}/settings', 'CircleController#CreateEditAction', ['circle' => CircleItem::REGEX_NAME], [
        'url' => CircleItem::REGEX_NAME,
        'title' => CircleItem::REGEX_TITLE,
        'type' => Regex::UNSIGNED_INT,
        'desc' => '.*'
    ], MimeType::Json);


    Route::Get('/{circle}', 'CircleController#Dashboard', ['circle' => CircleItem::REGEX_NAME]);
    Route::Post('/{circle}/img/{type}', 'ImageController#UploadCircle', ['circle' => CircleItem::REGEX_NAME, 'type' => '^cover|profile$'], ['type' => 'image/.+'], MimeType::Json);


    Route::Get('/{circle}/pages', 'ProjectController#IndexView');
    Route::Post('/{circle}/pages', 'ProjectController#Add', [], [
        'title' => ProjectItem::REGEX_TITLE,
        'title_alt' => '^$|' . ProjectItem::REGEX_TITLE,
        'url' => ProjectItem::REGEX_URL,
        'state' => '\d'
    ], MimeType::Json);
    Route::Get('/{circle}/tags', 'TagsController#Search', [], [
        'tag' => TagItem::REGEX_NAME
    ], MimeType::Json);
    Route::Post('/{circle}/tags', 'TagsController#Add', [], [
        'tag' => TagItem::REGEX_NAME
    ], MimeType::Json);

    Route::Get('/{circle}/{project}', 'ProjectController#EditView');
    Route::Post('/{circle}/{project}', 'ProjectController#EditAction', [], [
        'title' => ProjectItem::REGEX_TITLE,
        'title_alt' => '^$|' . ProjectItem::REGEX_TITLE,
        'url' => ProjectItem::REGEX_URL,
        'state' => '\d',
        'categories' => '.*',
        'synopsis' => '.*'
    ]);

    Route::Post('/{circle}/{project}/cover', 'ImageController#UploadAdminProject', [],
        ['filename' => '^$', 'type' => 'image/.+'], MimeType::Json);

    Route::Post('/{circle}/{project}/group', 'GroupController#Edit', [], [
        'title' => '.+', 'group' => GroupItem::REGEX_ID_OPTIONAL
    ], MimeType::Json);
    Route::Post('/{circle}/{project}/group/cover', 'ImageController#UploadAdminGroup', [],
        ['filename' => '^G-\d+$', 'type' => 'image/.+'], MimeType::Json);
    Route::Post('/{circle}/{project}/sort', 'ProjectController#SortEntryAndGroups', [], ['sort' => '.+']);

    #Project Chapters.
    Route::Get('/{circle}/{project}/chapter', 'ProjectController#ChapterView');
    Route::Post('/{circle}/{project}/chapter', 'ProjectController#ChapterAction', [], [
        'title' => '.*',
        'content' => '.*'
    ]);
    Route::Get('/{circle}/{project}/{chapter}', 'ProjectController#ChapterView', [
        'chapter' => EntryItem::REGEX_NAME
    ]);
    Route::Post('/{circle}/{project}/{chapter}', 'ProjectController#ChapterAction', [
        'project' => ProjectItem::REGEX_URL,
        'chapter' => EntryItem::REGEX_NAME
    ], ['title' => '.*', 'content' => '.*']);

    #Chapter Images
    Route::Post('/{circle}/{project}/img', 'ImageController#UploadChapter', [],
        null, MimeType::Json);
}
