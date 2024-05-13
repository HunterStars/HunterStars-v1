<?php

namespace HS\app\models\admin;

use HS\app\models\items\GroupItem;
use HS\config\DBAccount;
use HS\libs\database\DB;
use PDO;

class EntryGroupModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::entry);
    }

    public function GetList(string $user_id, string $circle_id, string $project_id): array
    {
        return $this->SelectAll('CALL entry_GetGroups(:oid, :cid, :pid)', [
            'pid' => $project_id,
            'cid' => $circle_id,
            'oid' => $user_id,
        ], GroupItem::class);
    }
}