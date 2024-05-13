<?php

namespace HS\config\enums;

enum AppImageDirs: string
{
    case GENERAL = 'basic';
    case ICON_PAY = 'icon/pay';
    case PROFILE = 'upload/profile';

    public static function ToCasesArray(): array
    {
        return array_map(fn($item) => $item->value, self::cases());
    }
}