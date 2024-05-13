<?php

namespace HS\app\models\admin\image;

use HS\config\DBAccount;
use HS\libs\core\Session;
use HS\libs\database\DB;
use PDO;

class GroupCoverImageModel extends ImageModel
{
    private string $UserID;
    private string $CircleID;
    private string $ProjectID;
    private int $GroupID;

    public function __construct(string $circle_id, string $project_id, int $group_id, DBAccount|DB|PDO $account = null)
    {
        $this->UserID = Session::GetOnlyRead()->User->ID;
        $this->CircleID = $circle_id;
        $this->ProjectID = $project_id;
        $this->GroupID = $group_id;
        parent::__construct($account);
    }

    public function Get(): ?string
    {
        $result = $this->SelectOnly('CALL image_GetGroupCover(?, ?, ?, ?)', [
            $this->UserID,$this->CircleID,$this->ProjectID, $this->GroupID
        ]);
        return is_array($result) ? null : $result;
    }

    public function Edit(string $new_name): bool
    {
        return $this->SelectOnly('SELECT image_EditGroupCover(?, ?, ?, ?, ?)', [
                $this->UserID,$this->CircleID,$this->ProjectID, $this->GroupID, $new_name
            ]) === 1;
    }
}