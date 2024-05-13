<?php

namespace HS\libs\helpers;

class DataUtils
{
    public static function GetShortName(string $firstname, string $lastname): string
    {
        return explode(" ", $firstname)[0] . " " . explode(" ", $lastname)[0];
    }
}