<?php

namespace HS\app\models\admin;

use HS\app\models\items\ProjectItem;
use HS\app\models\items\StatusItem;
use HS\app\models\items\TagItem;
use HS\config\DBAccount;
use HS\config\LogFile;
use HS\libs\database\DB;
use HS\libs\database\DBTools;
use HS\libs\helpers\Logger;
use HS\libs\helpers\Random;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use PDO;
use PDOStatement;

class ProjectModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::project);
    }

    public function GetID(string $user_id, string $circle_id, string $project_name, bool $responseHttp403): ?string
    {
        $result = $this->SelectOnly('CALL project_GetID(:user, :circle, :project)', [
            'user' => $user_id, 'circle' => $circle_id, 'project' => $project_name
        ]);
        if ($responseHttp403) $this->SetError403IfNecessary($result, $user_id, $circle_id, $project_name);
        return empty($result) ? null : $result;
    }

    public function GetCircleID(string $user_id, string $circle_name, string $project_name, bool $responseHttp403): ?string
    {
        return CircleModel::GetStaticID($this, $user_id, $circle_name, $project_name, $responseHttp403);
    }

    public function Get(string $user_id, string $circle_id, string $project_name, bool $responseHttp403): ?ProjectItem
    {
        $result = $this->SelectOnly('CALL project_Get(:user, :circle, :project)', [
            'user' => $user_id, 'circle' => $circle_id, 'project' => $project_name
        ], ProjectItem::class);
        if ($responseHttp403) $this->SetError403IfNecessary($result, $user_id, $circle_id, $project_name);
        return empty($result) ? null : $result;
    }

    public function GetMostPopulars(string $user_id, string $circle_id, int $limit): array
    {
        return $this->SelectAll('CALL project_GetMostPopulars(?, ?, ?)', [$user_id, $circle_id, $limit], ProjectItem::class);
    }

    public function GetList(string $user_id, string $circle_id): array
    {
        return $this->SelectAll('CALL project_GetList(?, ?)', [$user_id, $circle_id], ProjectItem::class);
    }

    public function GetStatusCatalog(int $circle_type): array
    {
        return $this->SelectAll('CALL project_GetStatusCatalog(?)', [$circle_type], StatusItem::class);
    }

    public function ExistsUrl(string $url): bool
    {
        return $this->SelectOnly('SELECT project_ExistsName(?)', [strtolower($url)]) === 1;
    }

    public function Add(string $user_id, string $circle_id, string $title, string $title_alt, string $url, int $state_id): bool
    {
        return $this->SelectOnly('SELECT project_Add(:oid, :cid, :pid, :title, :title_alt, :url, :state)', [
            'pid' => Random::GetTextID(),
            'cid' => $circle_id,
            'oid' => $user_id,
            'title' => $title,
            'title_alt' => $title_alt,
            'url' => strtolower($url),
            'state' => $state_id
        ]);
    }

    public function GetCategories(string $user_id, string $circle_id, string $project_id): array
    {
        return $this->SelectAll('CALL project_GetCategories(?, ?, ?)', [$user_id, $circle_id, $project_id], TagItem::class);
    }

    public function Edit(string $user_id, string $circle_id, string $project_id, string $title, string $title_alt, string $url, int $state_id, string $synopsis): bool
    {
        return $this->SelectOnly('SELECT project_Edit(:oid, :cid, :pid, :title, :title_alt, :url, :state, :synopsis)', [
                'pid' => $project_id,
                'cid' => $circle_id,
                'oid' => $user_id,
                'title' => $title,
                'title_alt' => $title_alt,
                'url' => strtolower($url),
                'state' => $state_id,
                'synopsis' => $synopsis
            ]) === 1;
    }

    public function SaveSort(string $user_id, string $circle_id, string $project_id, array $sorted_tree): bool
    {
        return $this->Transaction(function () use ($circle_id, $user_id, $project_id, $sorted_tree) {
            //Preparando consulta.
            $sentence = $this->GetPDO()->prepare('UPDATE p_entry SET order_index = :order, entry_group = :group WHERE name = :entry and project = :project');

            //Recorriendo primer nivel del árbol.
            for ($index = 0; $index < count($sorted_tree); $index++) {
                $position = $index + 1;
                $node = $sorted_tree[$index];

                if (is_string($node))
                    self::SortTreeNode($sentence, $project_id, 0, $position, $node);
                else {
                    //Recorriendo segundo nivel del árbol.
                    $group_key = array_key_first(get_object_vars($node));
                    $group = str_replace('G-', '', $group_key);

                    foreach ($node->$group_key as $sub_index => $sub_node)
                        self::SortTreeNode($sentence, $project_id, $group, $position + ($sub_index / 100000), $sub_node);
                }
            }

            return true;
        });
    }

    private static function SortTreeNode(PDOStatement $sentence, string $project_id, int $group_id, float $position, string $node): void
    {
        //Vinculando parámetros de la consulta.
        DBTools::BindValue($sentence, ['project' => $project_id, 'group' => $group_id, 'entry' => trim($node), 'order' => $position]);

        //Ejecutando consulta.
        $sentence->execute();
    }


    /**
     * @param mixed $result
     * @param string $user_id
     * @param string $project_name
     * @param string $circle_id
     * @return void
     */
    public function SetError403IfNecessary(mixed $result, string $user_id, string $circle_id, string $project_name): void
    {
        if (empty($result)) {
            Logger::WriteString(LogFile::NO_ACCESS, "El usuario \"$user_id\" no tiene acceso al proyecto \"$project_name\" del circulo \"$circle_id\"");
            Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
            die;
        }
    }
}