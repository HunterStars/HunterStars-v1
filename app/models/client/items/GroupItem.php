<?php

namespace HS\app\models\client\items;

use HS\libs\helpers\UrlFiles;

class GroupItem
{
    public int $ID;
    public string $Cover;
    public string $Title;

    public function GetCoverUrl(string $project): string
    {
        return !empty($this->Cover) ? UrlFiles::GetProjectIMG($project, $this->Cover): NovelItem::EMPTY_COVER_URL;
    }
}