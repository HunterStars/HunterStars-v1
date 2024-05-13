<?php

namespace HS\libs\net;

use Exception;
use HS\config\LogFile;
use HS\libs\helpers\Logger;
use HS\libs\helpers\MimeType;

enum HttpResponse: string
{
    case C403_FORBIDDEN = '403 Forbidden';
    case C404_NOTFOUND = '404 Not Found';
    case C500_INTERNAL_SERVER_ERROR = '500 Internal Server Error';

    public function SetCode(): void
    {
        header("HTTP/1.0 " . $this->value);
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
        header("HTTP/1.0 303 See Other");
        header("Location: $url");
        die();
    }
}