<?php

namespace HS\app\models\admin;

use HS\app\models\items\EntryItem;
use HS\config\DBAccount;
use HS\config\LogFile;
use HS\libs\database\DB;
use HS\libs\helpers\Logger;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use PDO;

class EntryModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::entry);
    }

    public function GetMostPopularEntries(string $user_id, string $circle_id, int $limit): array
    {
        return $this->SelectAll('CALL entry_GetMostPopulars(?, ?, ?)', [$user_id, $circle_id, $limit], EntryItem::class);
    }

    public function Get(string $user_id, string $circle_id, string $project_id, string $chapter_name, bool $responseHttp403): ?EntryItem
    {
        $result = $this->SelectOnly('CALL entry_Get(:uid, :cid, :pid, :chapter)', [
            'uid' => $user_id,
            'cid' => $circle_id,
            'pid' => $project_id,
            'chapter' => $chapter_name
        ], EntryItem::class);
        if ($responseHttp403) $this->SetError403IfNecessary($result, $user_id, $project_id, $chapter_name);
        return empty($result) ? null : $result;
    }

    public function GetList(string $user_id, string $circle_id, string $project_id): array
    {
        return $this->SelectAll('CALL entry_GetList(:oid, :cid, :pid)', [
            'pid' => $project_id,
            'cid' => $circle_id,
            'oid' => $user_id,
        ], EntryItem::class);
    }

    public function Add(string $user_id, string $circle_id, string $project_id, string $title, string $content): string|null
    {
        return $this->SelectOnly('SELECT entry_Add(:uid, :cid, :pid, :title, :content)', [
            'uid' => $user_id,
            'cid' => $circle_id,
            'pid' => $project_id,
            'title' => $title,
            'content' => $content
        ]);
    }

    public function Edit(string $user_id, string $circle_id, string $project_id, string $name, string $title, string $content): bool
    {
        return $this->SelectOnly('SELECT entry_Edit(:uid, :cid, :pid, :name, :title, :content)', [
                'uid' => $user_id,
                'cid' => $circle_id,
                'pid' => $project_id,
                'name' => $name,
                'title' => $title,
                'content' => $content
            ]) == 1;
    }

    public function SetError403IfNecessary(mixed $result, string $user_id, string $project_id, string $entry_name): void
    {
        if (empty($result)) {
            Logger::WriteString(LogFile::NO_ACCESS, "Acceso no autorizado a la entrada \"$entry_name\" del proyecto \"$project_id\" por \"$user_id\"");
            Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
            die;
        }
    }
}