<?php

namespace HS\app\models\items;

use HS\libs\helpers\DataUtils;

class CommentItem
{
    public int $ID;
    public string $FirstName;
    public string $LastName;
    public string $Content;
    public string $CreatedAt;
    public bool $IsMember;

    public function GetShortName(): string
    {
        return DataUtils::GetShortName($this->FirstName, $this->LastName);
    }

    public function GetID(): string
    {
        $md5 = MD5($this->ID);
        return $md5 . dechex($this->ID + preg_replace('/[^0-9]/', '', substr($md5, 0, 8)));
    }

    public static function DecodeID(string $id): int
    {
        $md5 = preg_replace('/[^0-9]/', '', substr($id, 0, 8));
        $num = hexdec(substr($id, 32));
        return $num - $md5;
    }
}