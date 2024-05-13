<?php

namespace HS\app\models\admin\image;

use HS\config\DBAccount;
use HS\libs\core\Session;
use HS\libs\database\DB;
use PDO;

class ProjectCoverImageModel extends ImageModel
{
    private string $UserID;
    private string $CircleID;
    private string $ProjectID;

    public function __construct(string $circle_id, string $project_id, DBAccount|DB|PDO $account = null)
    {
        $this->UserID = Session::GetOnlyRead()->User->ID;
        $this->CircleID = $circle_id;
        $this->ProjectID = $project_id;
        parent::__construct($account);
    }

    public function Get(): ?string
    {
        $result = $this->SelectOnly('CALL image_GetProjectCover(:user, :circle, :project)', [
            'user' => $this->UserID,
            'circle' => $this->CircleID,
            'project' => $this->ProjectID
        ]);
        return is_array($result) ? null : $result;
    }

    public function Edit(string $new_name): bool
    {
        return $this->SelectOnly('SELECT image_EditProjectCover(:user, :circle, :project, :url)', [
                'user' => $this->UserID,
                'circle' => $this->CircleID,
                'project' => $this->ProjectID,
                'url' => $new_name
            ]) === 1;
    }
}