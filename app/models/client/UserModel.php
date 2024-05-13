<?php

namespace HS\app\models\client;

use HS\config\DBAccount;
use HS\libs\database\DB;

class UserModel extends DB
{
    public function __construct(DBAccount|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::user);
    }

    public function AddFavorite(string $user_id, string $project_id)
    {
        $this->Run('CALL user_AddFavorite(?, ?)', [$user_id, $project_id]);
    }

    public function RemoveFavorite(string $user_id, string $project_id)
    {
        $this->Run('CALL user_RemoveFavorite(?, ?)', [$user_id, $project_id]);
    }
}