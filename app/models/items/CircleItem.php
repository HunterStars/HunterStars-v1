<?php

namespace HS\app\models\items;

use HS\libs\helpers\UrlFiles;

class CircleItem
{
    const REGEX_TITLE = '.{2,50}';
    const REGEX_NAME = '^[\w\-]{2,50}$';

    const BLACK_LIST = ['circle', 'project', 'user'];

    public string $ID;
    public string $Name;
    public string $Title;
    public int $TypeID;
    public string $TypeName;
    public ?string $ProfileImg;
    public ?string $CoverImg;
    public ?string $Description;

    //De relación.
    public int $ProjectsCount;
    public int $MembersCount;

    const NaturalTypeName = [
        'anime' => 'Animes',
        'novels' => 'Novelas',
        'game' => 'Juegos',
        'manga' => 'Mangas',
        'music' => 'Musica'
    ];

    public static function IsValidName(string $name): bool
    {
        return true;
    }

    //Calculado.
    public function GetNaturalTypeName(): string
    {
        return self::NaturalTypeName[$this->TypeName] ?? '???';
    }

    public function GetSingularTypeName(): string
    {
        $typeName = $this->GetNaturalTypeName();
        return str_ends_with($typeName, 's') ? substr($typeName, 0, strlen($typeName) - 1) : $typeName;
    }

    public function GetCoverUrl(): string
    {
        return !empty($this->CoverImg) ? UrlFiles::GetCircleAdminIMG($this->Name, $this->CoverImg) : '';
    }

    public function GetProfileUrl(): string
    {
        return !empty($this->ProfileImg) ? UrlFiles::GetCircleAdminIMG($this->Name, $this->ProfileImg) : '';
    }
}