<?php

namespace HS\app\models\admin;

use HS\config\DBAccount;
use HS\libs\database\DB;
use PDO;

class GroupModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::project);
    }

    public function Add(string $user_id, string $circle_id, string $project_id, string $title): ?int
    {
        return $this->SelectOnly('SELECT group_Add(:uid, :cid, :pid, :title)', [
            'pid' => $project_id,
            'cid' => $circle_id,
            'uid' => $user_id,
            'title' => $title
        ]);
    }

    public function Edit(string $user_id, string $circle_id, string $project_id, int $group_id, string $title): int
    {
        return $this->SelectOnly('SELECT group_Edit(:uid, :cid, :pid, :group, :title)', [
            'pid' => $project_id,
            'cid' => $circle_id,
            'uid' => $user_id,
            'group' => $group_id,
            'title' => $title
        ]);
    }
}