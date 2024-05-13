<?php

namespace HS\app\models\admin;

use HS\app\models\admin\items\CircleDashboardItem;
use HS\app\models\items\CircleItem;
use HS\app\models\items\CircleTypeItem;
use HS\config\DBAccount;
use HS\config\LogFile;
use HS\libs\database\DB;
use HS\libs\helpers\Logger;
use HS\libs\helpers\Random;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use PDO;

class CircleModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::circle);
    }

    public function Get(string $user_id, string $circle_name): ?CircleItem
    {
        return $this->SelectOnly('CALL circle_Get(?, ?)', [$user_id, $circle_name], CircleItem::class);
    }

    public function GetID(string $user_id, string $circle_name, bool $responseHttp403 = false): ?string
    {
        return self::GetStaticID($this, $user_id, $circle_name, '', $responseHttp403);
    }

    public static function GetStaticID(CircleModel|ProjectModel $model, string $user_id, string $circle_name, string $project_name, bool $responseHttp403): ?string
    {
        $result = $model->SelectOnly('CALL circle_GetID(?, ?)', [$user_id, $circle_name]);
        if ($responseHttp403) $model->SetError403IfNecessary($result, $user_id, $circle_name, $project_name);
        return empty($result) ? null : $result;
    }

    public function GetBasic(string $user_id, string $circle_name, bool $responseHttp403 = false): ?CircleItem
    {
        $result = $this->SelectOnly('CALL circle_GetBasicData(?, ?)', [$user_id, $circle_name], CircleItem::class);
        if ($responseHttp403) $this->SetError403IfNecessary($result, $user_id, $circle_name);
        return empty($result) ? null : $result;
    }

    public function GetList(string $user_id): array
    {
        return $this->SelectAll('CALL circle_GetList(?)', [$user_id], CircleItem::class);
    }

    public function GetTypes(): array
    {
        return $this->SelectAll('CALL circle_GetTypes()', [], CircleTypeItem::class);
    }

    public function GetCovers(string $user_id, string $circle_name): ?CircleItem
    {
        return $this->SelectOnly('CALL circle_GetCovers(?, ?)', [$user_id, $circle_name], CircleItem::class);
    }

    public function ExistsName(string $name): bool
    {
        return $this->SelectOnly('SELECT circle_ExistsName(?)', [$name]) === 1;
    }

    public function Create(string $user_id, string $name, string $title, int $type): void
    {
        $this->Run('CALL circle_Create(:uid, :cid, :name, :title, :type)', [
            'uid' => $user_id,
            'cid' => Random::GetTextID(),
            'name' => $name,
            'title' => $title,
            'type' => $type
        ]);
    }

    public function Edit(string $user_id, string $circle_id, string $name, string $title, int $type, string $description): bool
    {
        return $this->SelectOnly('SELECT circle_Edit(:uid, :cid, :name, :title, :type, :desc)', [
                'uid' => $user_id,
                'cid' => $circle_id,
                'name' => $name,
                'title' => $title,
                'type' => $type,
                'desc' => $description
            ]) === 1;
    }

    public function SetCover(string $user_id, string $circle_id, string $cover): bool
    {
        return $this->SelectOnly('SELECT circle_UpdateCover(?, ?, ?)', [$user_id, $circle_id, $cover]) === 1;
    }

    public function SetProfile(string $user_id, string $circle_id, string $profile_img): bool
    {
        return $this->SelectOnly('SELECT circle_UpdateProfile(?, ?, ?)', [$user_id, $circle_id, $profile_img]) === 1;
    }

    public function GetDashboard(string $user_id, string $circle_name): ?CircleDashboardItem
    {
        return $this->SelectOnly('CALL circle_GetDashboard(?, ?)', [$user_id, $circle_name], CircleDashboardItem::class);
    }

    public function GetViewsOfLast30Days(string $user_id, string $circle_id): array
    {
        return $this->SelectAll('CALL circle_Get30DayViewsStats(?, ?)', [$user_id, $circle_id]);
    }

    /**
     * @param bool $responseHttp403
     * @param mixed $result
     * @param string $user_id
     * @param string $circle_name
     * @return void
     */
    public function SetError403IfNecessary(mixed $result, string $user_id, string $circle_name): void
    {
        if (empty($result)) {
            Logger::WriteString(LogFile::NO_ACCESS, "El usuario \"$user_id\" no tiene acceso al circulo \"$circle_name\"");
            Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
            die;
        }
    }
}