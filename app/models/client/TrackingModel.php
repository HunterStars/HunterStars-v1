<?php

namespace HS\app\models\client;

use HS\config\DBAccount;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\database\DB;
use HS\libs\helpers\Logger;
use HS\libs\helpers\Random;
use PDOException;

class TrackingModel
{
    public static function Project(string $circle_id, string $project_id): void
    {
        try {
            $db = new DB(DBAccount::tracking);
            $db->Run('CALL AddTracking(?, ?, ?, ?)', [self::GetSessionTrackID(), $circle_id, $project_id, '']);
            unset($db);
        } catch (PDOException $ex) {
            Logger::WriteException(LogFile::TRACKING, $ex);
        }
    }

    public static function ProjectChapter(string $circle_id, string $project_id, int $chapter_id): void
    {
        try {
            $db = new DB(DBAccount::tracking);
            $db->Run('CALL AddTracking(?, ?, ?, ?)', [self::GetSessionTrackID(), $circle_id, $project_id, $chapter_id]);
            unset($db);
        } catch (PDOException $ex) {
            Logger::WriteException(LogFile::TRACKING, $ex);
        }
    }

    private static function GetSessionTrackID(): string
    {
        $session = Session::Get();
        if (!isset($session->TrackID))
            $session->TrackID = Random::GetTextID();
        $session->Close();

        return $session->TrackID;
    }
}