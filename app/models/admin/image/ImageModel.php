<?php

namespace HS\app\models\admin\image;

use HS\config\DBAccount;
use HS\libs\database\DB;
use PDO;

abstract class ImageModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::image);
    }

    public abstract function Get(): ?string;

    public abstract function Edit(string $new_name) : bool;
}