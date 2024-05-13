<?php

namespace HS\libs\helpers;

use HS\config\enums\AppDirs;
use HS\config\enums\AppImageDirs;
use HS\config\enums\UploadImageDir;
use HS\libs\io\Path;
use const HS\APP_PATH;

class AppFiles
{
    public static function GetAdminSCSS(string $filename): string
    {
        return Path::CombineRoot(AppDirs::FILES, 'admin/scss', str_replace('.', '', $filename) . '.scss');
    }

    public static function GetClientSCSS(string $filename): string
    {
        return Path::CombineRoot(AppDirs::FILES, 'client/scss', str_replace('.', '', $filename) . '.scss');
    }

    public static function GetSCSS(string $filename): string
    {
        return Path::CombineRoot(AppDirs::FILES, 'common/scss', str_replace('.', '', $filename) . '.scss');
    }

    public static function GetUploadIMG(string $circle_id, string $project_id, string $filename): string
    {
        return Path::CombineRoot(AppDirs::UPLOAD_IMG, $circle_id, $project_id, $filename);
    }
}