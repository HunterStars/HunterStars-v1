<?php

namespace HS\app\models\items;

use DateTime;
use HS\libs\helpers\DataUtils;
use HS\libs\helpers\Validator;

class EntryItem
{
    const BLACK_LIST = ['cover', 'chapter', 'group', 'sort', 'img'];
    const REGEX_NAME = '^[\w\-]{1,32}$';

    public string $Name;
    public string $Title;
    public string $Content;
    public string $CreatedAt;
    public string $CreatorFirstName;
    public string $CreatorLastName;
    public string $ModifiedAt;
    public string $ModifierFirstName;
    public string $ModifierLastName;
    public int $OrderIndex;
    public int $Group;

    public string $ProjectName;
    public string $ProjectTitle;
    public string $Views;

    public static function IsValidUrl(string $url): bool
    {
        return Validator::IsValidUrlPath(self::REGEX_NAME, $url);
    }

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

    public function GetModifiedDateTime(bool $time = true): string
    {
        return (new DateTime($this->ModifiedAt))->format('d/m/Y' . ($time ? ' h:i A' : ''));
    }
}