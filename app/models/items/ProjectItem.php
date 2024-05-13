<?php

namespace HS\app\models\items;

use DateTime;
use HS\libs\helpers\DataUtils;
use HS\libs\helpers\UrlFiles;
use HS\libs\helpers\UrlMaker;
use HS\libs\helpers\Validator;

class ProjectItem
{
    const BLACK_LIST = [
        'pages',
        'tags',
        'novels',
        'circles',
        'settings',
        'user',
        'state',
        'comment'
    ];

    public string $ID;
    public string $Name;
    public string $Title;
    public string $TitleAlt;
    public ?string $Cover;
    public int $StateID;
    public string $StateName;
    public string $Synopsis;
    public int $Type;
    public string $TypeName;

    public string $CreatorFirstName;
    public string $CreatorLastName;
    public string $CreatedAt;

    public string $ModifierFirstName;
    public string $ModifierLastName;
    public string $ModifiedAt;

    //Relaciones.
    public array $Categories;

    //Tracking.
    public int $ChapterCount;
    public int $Views;

    //Constantes.
    const REGEX_TITLE = '^[\wñáéíóú¡!¿?\[\].,*+\-&;:()~ ]{2,150}$';
    const REGEX_URL = CircleItem::REGEX_NAME;

    public function GetCreatorName(): string
    {
        return DataUtils::GetShortName($this->CreatorFirstName, $this->CreatorLastName);
    }

    public function GetModifierName(): string
    {
        return DataUtils::GetShortName($this->ModifierFirstName, $this->ModifierLastName);
    }

    public function GetCreatedDate(): string
    {
        return (new DateTime($this->CreatedAt))->format('d/m/Y');
    }

    public function GetModifiedDate(): string
    {
        return (new DateTime($this->ModifiedAt))->format('d/m/Y');
    }

    public static function IsValidTitle(string $title): bool
    {
        return preg_match('#' . self::REGEX_TITLE . '#', $title) === 1;
    }

    public static function IsValidUrl(string $url): bool
    {
        return Validator::IsValidUrlPath(self::REGEX_URL, $url);
    }

    public function GetCoverUrl(string $circle): string
    {
        return !empty($this->Cover) ? UrlFiles::GetProjectAdminIMG($circle, $this->Name, $this->Cover) : '';
    }

    public function GetClientCoverUrl(): string
    {
        return !empty($this->Cover) ? UrlMaker::GetProjectCover($this->TypeName, $this->Name, $this->Cover) : '';
    }
}