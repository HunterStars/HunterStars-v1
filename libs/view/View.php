<?php

namespace HS\libs\view;

use HS\app\models\CircleModel;
use HS\app\models\client\items\NovelStateItem;
use HS\app\models\items\CircleItem;
use HS\app\models\items\EntryItem;
use HS\app\models\items\GroupItem;
use HS\app\models\items\ProjectItem;
use HS\app\models\ProjectModel;
use HS\libs\collection\PropertyCollection;

class View extends PropertyCollection
{
    //Privado
    private static ?ViewLayout $layout = null;
    private static null|ViewAdminData|ViewClientData $data = null;

    //Métodos estáticos.
    public static function GetData(): ViewAdminData
    {
        return is_null(self::$data) ? (self::$data = new ViewAdminData()) : self::$data;
    }

    public static function GetClientData(): ViewClientData
    {
        return is_null(self::$data) ? (self::$data = new ViewClientData()) : self::$data;
    }

    public static function GetLayout(): ViewLayout
    {
        return is_null(self::$layout) ? (self::$layout = new ViewLayout()) : self::$layout;
    }
}