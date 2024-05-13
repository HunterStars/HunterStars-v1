<?php

namespace HS\app\models\items;

use HS\libs\helpers\Regex;

class GroupItem
{
    const REGEX_ID_OPTIONAL = Regex::UNSIGNED_INT_OPTIONAL;

    public int $ID;
    public string $Title;
    public string $Cover;
}