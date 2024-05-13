<?php

namespace HS\libs\io;

use HS\config\enums\AppDirs;
use UnitEnum;
use const HS\APP_PATH;

class Path extends UrlPathBase
{
    public static function Combine(string|UnitEnum...$args): ?string
    {
        array_unshift($args, DIRECTORY_SEPARATOR);
        return call_user_func_array('self::CombineBase', $args);
    }

    public static function CombineRoot(string|UnitEnum...$args): ?string
    {
        array_unshift($args, DIRECTORY_SEPARATOR, APP_PATH);
        return call_user_func_array('self::CombineBase', $args);
    }

    public static function Trim(string $path): string
    {
        return self::TrimBase(DIRECTORY_SEPARATOR, $path);
    }

    public static function GetViewAdmin(string $path): string
    {
        return self::CombineRoot(AppDirs::VIEW, 'admin', $path);
    }

    public static function GetViewClient(string $path): string
    {
        return self::CombineRoot(AppDirs::VIEW, 'clients', $path . (!empty($path) ? '.php' : ''));
    }
}