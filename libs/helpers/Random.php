<?php

namespace HS\libs\helpers;

class Random
{
    public static function GetTextID(string $prefix = ''): string
    {
        try {
            $random_prefix = bin2hex(random_bytes(5)); //Len: 10
        } catch (\Exception) {
            $random_prefix = str_pad(dechex(mt_rand(0, mt_getrandmax())), 8, '0', STR_PAD_LEFT) . mt_rand(0, 9) . mt_rand(0, 9);
        }
        return (!empty($prefix) ? md5($prefix) : '') . strtoupper(str_replace('.', '', uniqid($random_prefix, true)));
    }
}