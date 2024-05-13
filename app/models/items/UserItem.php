<?php

namespace HS\app\models\items;

use HS\config\enums\AppRegex;
use HS\libs\helpers\DataUtils;
use HS\libs\helpers\Regex;

class UserItem
{
    public string $ID;
    public string $Nick;
    public ?string $Pass;
    public string $FirstName;
    public string $LastName;
    public string $Email;

    public static function IsValidNick(string $nick, bool $optional): bool
    {
        return Regex::Match(AppRegex::UserName, $nick, $optional);
    }

    public static function IsValidPassword(string $pass, bool $optional): bool
    {
        return Regex::Match(AppRegex::UserPass, $pass, $optional);
    }

    public function GetShortName(): string
    {
        return DataUtils::GetShortName($this->FirstName, $this->LastName);
    }
}