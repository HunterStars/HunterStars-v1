<?php

namespace HS\libs\view;

use HS\app\models\admin\items\CircleDashboardItem;
use HS\app\models\client\items\NovelItem;
use HS\app\models\items\CircleTypeItem;
use HS\app\models\items\StatusItem;
use HS\libs\collection\PropertyCollection;
use HS\app\models\items\CircleItem;
use HS\app\models\items\EntryItem;
use HS\app\models\items\GroupItem;
use HS\app\models\items\ProjectItem;

/**
 * @property CircleItem $CurrentCircle
 * @property CircleItem[] $Circles
 * @property CircleTypeItem[] $CircleTypes
 * @property ProjectItem[] $Projects
 * @property StatusItem[] $ProjectTypes
 * @property ProjectItem $Project
 * @property EntryItem $Entry
 * @property EntryItem[] $Entries
 * @property GroupItem[] $Groups
 * @property CircleDashboardItem|null $Dashboard
 * @property array $ViewsOfLast30Day
 * @property ProjectItem[] $Top5Projects
 * @property EntryItem[] $Top10Entries
 * */
class ViewAdminData extends PropertyCollection
{

}