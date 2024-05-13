<?php

namespace HS\app\models\admin\items;

class CircleDashboardItem
{
    public string $ID;
    public string $Name;
    public string $Title;
    public int $ProjectsCount;
    public int $MembersCount;
    public int $EntriesCount;
    public ?int $TotalViews;
    public ?int $ViewsToday;
    public ?int $ViewsYesterday;
    public ?int $ViewsCurrentWeek;
    public ?int $ViewsLastWeek;
    public ?int $ViewsCurrentMonth;
    public ?int $ViewsLastMonth;
}