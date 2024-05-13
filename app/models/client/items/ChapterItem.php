<?php

namespace HS\app\models\client\items;

class ChapterItem
{
    public int $ID;
    public string $ProjectName;
    public string $ProjectCover;
    public string $ProjectTitle;
    public string $Name;
    public string $Title;
    public string $Content;
    public string $CreatedDate;
    public float $Group;
    public bool $IsLast;

    public ?string $NextName;
    public ?string $NextTitle;
    public ?string $BackName;
    public ?string $BackTitle;
}