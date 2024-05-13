<?php

namespace HS\libs\security;

class HashCrypt
{
    private const COST = 10;

    public static function Hash(string $text): string|bool
    {
        return password_hash($text, PASSWORD_DEFAULT /*Blowfish*/, ['cost' => self::COST]);
    }

    public static function needReHash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => self::COST]);
    }
}