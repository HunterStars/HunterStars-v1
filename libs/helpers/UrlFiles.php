<?php

namespace HS\libs\helpers;

use HS\config\enums\AppDirs;
use HS\config\enums\AppImageDirs;
use HS\config\enums\SubDomains;
use HS\config\enums\UploadImageDir;
use HS\libs\io\Path;
use HS\libs\io\Url;

class UrlFiles
{
    public static function GetCircleAdminIMG(string $circle, string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), urlencode($circle), 'cover', urlencode($filename));
    }

    public static function GetProjectAdminIMG(string $circle, string $project, string $filename, SubDomains $domain = null): string
    {
        return htmlspecialchars(Url::Combine(self::GetRoot($domain), urlencode($circle), urlencode($project), 'cover', urlencode($filename)));
    }

    public static function GetProjectIMG(string $project, string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), 'novels', urlencode($project), 'cover', urlencode($filename));
    }

    public static function GetChapterIMG(string $project, string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), 'novels', urlencode($project), 'img', urlencode($filename));
    }

    public static function GetChapterAdminIMG(string $circle, string $project, string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetRoot($domain), urlencode($circle), urlencode($project), 'img', urlencode($filename));
    }

    public static function GetFile(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "$filename");
    }

    public static function GetCSS(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "css/$filename" . (empty($filename) ? '' : '.css'));
    }

    public static function GetJS(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "js/$filename" . (empty($filename) || str_ends_with($filename, '.mjs') ? '' : '.js'));
    }

    public static function GetVendorCSS(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "vendor/css/$filename" . (empty($filename) ? '' : '.css'));
    }

    public static function GetVendorFont(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "vendor/fonts/$filename" . (empty($filename) ? '' : '.css'));
    }

    public static function GetVendorJS(?string $filename, SubDomains $domain = null): string
    {
        return Url::Combine(self::GetFilePath($domain), "vendor/js/$filename" . (empty($filename) || str_ends_with($filename, '.mjs') ? '' : '.js'));
    }

    public static function GetFilePath(?SubDomains $domain = null): string
    {
        return is_null($domain) || $_SERVER['SERVER_NAME'] == $domain->value ? AppDirs::FILES->value : Url::Combine(self::GetCurrentProtocol() . $domain->value, AppDirs::FILES);
    }

    public static function GetUploadPath(?SubDomains $domain = null): string
    {
        return is_null($domain) || $_SERVER['SERVER_NAME'] == $domain->value ? AppDirs::UPLOAD->value : Url::Combine(self::GetCurrentProtocol() . $domain->value, AppDirs::UPLOAD);
    }

    public static function GetRoot(SubDomains $domain = null): string
    {
        return is_null($domain) || $_SERVER['SERVER_NAME'] == $domain->value ? '/' : self::GetCurrentProtocol() . $domain->value;
    }

    private static function GetCurrentProtocol(): string
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    }
}