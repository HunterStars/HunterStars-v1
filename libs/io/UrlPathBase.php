<?php

namespace HS\libs\io;

use UnitEnum;

abstract class UrlPathBase
{
    protected static function CombineBase(string $separator, string|UnitEnum...$args): ?string
    {
        $paths = $args;
        $paths = array_map(fn($path) => str_replace(["\\", "/"], $separator, is_a($path, UnitEnum::class) ? $path->value : $path), $paths);
        $path = implode($separator, array_map(fn($path) => self::trimSlash($separator, $path), $paths));
        if (!empty($paths) && $paths[0][0] === $separator && $path[0] !== $separator)
            $path = $separator . $path;
        return empty($path) ? NULL : str_replace("\\\\", $separator, $path);
    }

    protected static function TrimBase(string $separator, string $path): string
    {
        if (empty($path = trim($path)))
            return '';

        $path = str_replace(["\\", "/"], $separator, $path);
        $end = $path[strlen($path) - 1] === $separator ? -1 : strlen($path);

        return substr($path, 0, $end);
    }

    protected static function trimSlash(string $separator, string $path): string
    {
        if (strlen($path = trim($path)) == 0) return '';

        $start = $path[0] === $separator ? 1 : 0;
        $end = $path[strlen($path) - 1] === $separator ? -1 : strlen($path);

        return substr($path, $start, $end);
    }
}