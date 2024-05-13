<?php

namespace HS\app\models\client\items;

use HS\libs\helpers\UrlFiles;

class NovelItem
{
    const EMPTY_COVER_URL = '/files/img/basic/novel-cover.png';

    public string $ID;
    public string $CircleID;
    public string $Name;
    public string $Cover;
    public string $Title;
    public string $TitleAlt;
    public string $Synopsis;
    public int $StateID;
    public string $CreatedAt;
    public bool $IsFavorite;

    //Atributos de vistas.
    public int $ChapterCount;
    public int $Views;

    //Propiedades calculadas.
    public NovelStateItem $StateItem;

    //Propiedades manuales.
    /**
     * @var CategoryItem[]
     */
    public array $Categories;

    /**
     * @var GroupItem[]
     */
    public array $Groups;

    public array $Entries;

    public function __construct()
    {
        if (isset($this->StateID))
            $this->StateItem = new NovelStateItem($this->StateID);
    }

    public function GetCoverUrl(): string
    {
        return htmlspecialchars(!empty($this->Cover) ? UrlFiles::GetProjectIMG($this->Name, $this->Cover) : self::EMPTY_COVER_URL);
    }
}