<?php

namespace HS\libs\helpers;

class FSLimit
{
    public static function ini_set(string $path): void
    {
        if (OperatingSystem::GetCurrent() == OperatingSystem::WIN)
            ini_set('open_basedir', $path . PATH_SEPARATOR . 'c:' . $_SERVER['TMP']);
        else
            ini_set('open_basedir', $path . PATH_SEPARATOR . sys_get_temp_dir());
    }
}