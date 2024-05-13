<?php

namespace HS\libs\helpers;

use HS\config\enums\AppRegex;

class Regex
{
    const INT = '^-?\d+$';
    const INT_OPTIONAL = '^-?\d*$';
    const UNSIGNED_INT = '^\d+$';
    const UNSIGNED_INT_OPTIONAL = '^-?\d*$';

    /**
     * Escapa una cadena de forma que pueda ser interpretada literalmente en una expresion regex.
     * @param string $text
     * @param array $excepto [OPCIONAL]<br>
     * Array de caracteres que no seran escapados en la cadena objetivo.
     * @return string
     * Devuelve una cadena con los caracteres utilizados en una regex escapados, excepto por los
     * especificados en <var>$excepto</var>.
     */
    static function Escape(string $text, string $excepto = ''): string
    {
        $chars = array_diff(['\\', '^', '$', '.', '[', ']', '|', '(', ')', '?', '*', '+', '{', '}'], str_split($excepto, 1));
        $chars_replace = array_map(fn($char) => "\\$char", $chars);

        return str_replace($chars, $chars_replace, $text);
    }

    public static function Match(AppRegex|string $regex, string $text, bool $optional = false): bool
    {
        $regex = is_a($regex, AppRegex::class) ? $regex->value : $regex;

        return $optional && empty($text) || (preg_match("#$regex#", $text) === 1);
    }
}