<?php

namespace HS\libs\helpers;

class Json
{
    public static function GetJsonWarning(int $code, string $msg): string
    {
        return json_encode(['success' => false, 'code' => $code, 'warning' => $msg]);
    }

    public static function GetJsonError(int $code, string $msg): string
    {
        return json_encode(['success' => false, 'code' => $code, 'error' => $msg]);
    }

    public static function GetJsonSuccess(array $extra = []): string
    {
        return json_encode(['success' => true] + $extra);
    }
}