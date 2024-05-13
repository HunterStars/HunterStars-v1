<?php

namespace HS\libs\view;

use HS\app\models\client\items\ChapterItem;
use HS\app\models\client\items\CircleItem;
use HS\app\models\client\items\NovelItem;
use HS\app\models\client\items\NovelStateItem;
use HS\app\models\items\CommentItem;
use HS\app\models\items\ProjectItem;
use HS\libs\collection\PropertyCollection;

/**
 * @property NovelStateItem[] $NovelStateCount
 * @property ChapterItem[] $LastChapters
 * @property NovelItem[] $LastNovels
 * @property NovelItem $Novel
 * @property CircleItem $Circle
 * @property ChapterItem $Chapter
 * @property NovelItem[] $MostPopularNovels
 * @property string $CurrentNick
 * @property ProjectItem[] $Favorites
 * @property CommentItem[] $Comments
 */
class ViewClientData extends PropertyCollection
{

}