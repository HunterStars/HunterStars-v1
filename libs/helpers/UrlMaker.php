<?php

namespace HS\libs\helpers;

use HS\config\enums\SubDomains;
use HS\libs\io\Url;

class UrlMaker
{

    public static function GetProject(string $typeName, string $project, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), urlencode($typeName), urlencode($project));
    }

    public static function GetProjectCover(string $typeName, string $project, string $img, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), urlencode($typeName), urlencode($project), 'cover', urlencode($img));
    }

    public static function GetRoot(SubDomains $domain = null): string
    {
        return is_null($domain) || $_SERVER['SERVER_NAME'] == $domain->value ? '/' : self::GetCurrentProtocol() . $domain->value;
    }

    public static function GetCurrentProtocol(): string
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    }
}