<?php

namespace HS\libs\net;

use HS\libs\helpers\MimeType;

class Http
{
    public static function SetResponseCode(HttpStatus|int $status): void
    {
        http_response_code(is_a($status, HttpStatus::class) ? $status->value : $status);
    }

    public static function SetHeader(string $title, string $content): void
    {
        header("$title: $content");
    }

    public static function SetContentType(MimeType|string $mimeType): void
    {
        header("content-type: " . (is_a($mimeType, MimeType::class) ? $mimeType->value : $mimeType));
    }

    public static function Redirect(string $url): never
    {
        http_response_code(HttpStatus::C303_SEE_OTHER->value);
        header("Location: $url");
        die();
    }
}