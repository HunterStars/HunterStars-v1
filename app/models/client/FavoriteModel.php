<?php

namespace HS\app\models\client;

use HS\app\models\items\ProjectItem;
use HS\config\DBAccount;
use HS\libs\database\DB;

class FavoriteModel extends DB
{
    public function __construct(DBAccount|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::user);
    }

    public function GetAll(string $user_id): array
    {
        return $this->SelectAll('CALL user_GetFavorites(?, NULL)', [$user_id], ProjectItem::class);
    }
}