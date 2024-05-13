<?php

namespace HS\libs\io;

use UnitEnum;

class Url extends UrlPathBase
{
    public static function Relative(string $url, bool $escape = true): string
    {
        $url = Url::Combine($_SERVER['REQUEST_URI'], $url);
        return $escape ? htmlspecialchars($url) : $url;
    }

    public static function Combine(string|UnitEnum...$args): ?string
    {
        array_unshift($args, '/');
        return call_user_func_array('self::CombineBase', $args);
    }

    public static function Trim(string $path): string
    {
        return self::TrimBase('/', $path);
    }
}