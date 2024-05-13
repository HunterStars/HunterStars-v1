<?php

namespace HS\app\models\client;

use HS\app\models\client\items\CategoryItem;
use HS\app\models\client\items\ChapterItem;
use HS\app\models\client\items\CircleItem;
use HS\app\models\client\items\DownloadPDFItem;
use HS\app\models\client\items\GroupItem;
use HS\app\models\client\items\ImageItem;
use HS\app\models\client\items\NovelItem;
use HS\app\models\client\items\NovelStateItem;
use HS\app\models\items\CommentItem;
use HS\config\DBAccount;
use HS\libs\database\DB;

class NovelModel extends DB
{
    public function __construct(DBAccount|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::novel);
    }

    //Contadores
    public function GetStateCounts(): array
    {
        return $this->SelectAll('CALL novel_GetGeneralCountByState()', [], NovelStateItem::class);
    }

    public function GetLastChapters(int $limit, int $offset): array
    {
        return $this->SelectAll('CALL novel_GetLastChapters(?, ?)', [$limit, $offset], ChapterItem::class);
    }

    public function GetLast(int $limit, int $offset): array
    {
        return $this->SelectAll('CALL novel_GetLast(?, ?)', [$limit, $offset], NovelItem::class);
    }

    //Tops.
    public function GetMostPopularList(int $limit): array
    {
        return $this->SelectAll('CALL novel_GetMostPopulars(?)', [$limit], NovelItem::class);
    }

    public function GetImage(string $project, string $filename): ?ImageItem
    {
        return $this->SelectOnly('CALL novel_GetImageIfExist(?, ?)', [$project, $filename], ImageItem::class);
    }

    public function Get(string $project, ?string $user_id): ?NovelItem
    {
        return $this->SelectOnly('CALL novel_Get(?, ?)', [$project, $user_id], NovelItem::class);
    }

    public function GetID(string $project): ?string
    {
        $result = $this->SelectOnly('CALL novel_GetID(?)', [$project]);
        return empty($result) ? null : $result;
    }

    //Funcionales.
    public function GetCategories(string $project_id): array
    {
        return $this->SelectAll('CALL novel_GetCategories(?)', [$project_id], CategoryItem::class);
    }

    public function GetAllChapters(string $project_id): array
    {
        return $this->SelectAll('CALL novel_GetAllChapters(?)', [$project_id], ChapterItem::class);
    }

    public function GetAllChaptersWithContent(string $project_id, int $group_id = null): array
    {
        if (is_null($group_id))
            return $this->SelectAll('CALL novel_GetAllChaptersWithContent(?)', [$project_id], ChapterItem::class);
        else
            return $this->SelectAll('CALL novel_GetChaptersOfGroupWithContent(?, ?)', [$project_id, $group_id], ChapterItem::class);
    }

    public function GetChapterGroups(string $project_id): array
    {
        return $this->SelectAll('CALL novel_GetGroups(?)', [$project_id], GroupItem::class);
    }

    public function GetNovelsCount(string $circle_id): ?CircleItem
    {
        return $this->SelectOnly('CALL novel_CountCircleProjects(?)', [$circle_id], CircleItem::class);
    }

    public function GetChapter(string $project_id, string $chapter_name): ?ChapterItem
    {
        return $this->SelectOnly('CALL novel_GetChapter(?, ?)', [$project_id, $chapter_name], ChapterItem::class);
    }

    public function GetDataForPDF(string $project_name, ?int $group_id): ?DownloadPDFItem
    {
        return $this->SelectOnly('CALL novel_GetPDFData(?, ?)', [$project_name, $group_id], DownloadPDFItem::class);
    }

    public function AddComment(string $user_id, string $project_id, ?string $chapter_name, string $content, ?int $parent_comment): ?CommentItem
    {
        return $this->SelectOnly('CALL comment_Add(:uid, :pid, :chapter, :text, :parent)', [
            'uid' => $user_id,
            'pid' => $project_id,
            'chapter' => $chapter_name,
            'text' => $content,
            'parent' => $parent_comment
        ], CommentItem::class);
    }

    public function GetAllComments(string $project_id, ?string $chapter_id): array
    {
        return $this->SelectAllGroupBy('CALL comment_GetAll(?, ?)', [$project_id, $chapter_id], CommentItem::class);
    }
}