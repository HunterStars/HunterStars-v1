<?php

namespace HS\app\controllers\admin;

use HS\app\models\admin\TagModel;
use HS\app\models\items\CircleItem;
use HS\app\models\items\TagItem;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use PDOException;

class TagsController
{
    public function Add(string $circle): never
    {
        #Obteniendo parámetro post.
        $name = $_POST['tag'] ?? die(Json::GetJsonWarning(1, 'Datos faltantes.'));

        #Verificando los parámetros.
        if (!CircleItem::IsValidName($circle) || !TagItem::IsValidName($name))
            die(Json::GetJsonWarning(2, 'Etiqueta no valida.'));

        try {
            $user_id = Session::GetOnlyRead()->User->ID;
            $tag_model = new TagModel();

            if ($tag_model->Add($user_id, $circle, $name)) {
                $tag_id = $tag_model->GetID($user_id, $circle, $name);

                if (!empty($tag_id))
                    die(Json::GetJsonSuccess(['code' => $tag_id]));
                else
                    die(Json::GetJsonWarning(3, 'Etiqueta agregada, pero no conseguida.'));
            } else
                die(Json::GetJsonWarning(4, 'No tiene suficientes privilegios.'));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function Search(string $circle): never
    {
        #Obteniendo parámetro post.
        $name = $_GET['tag'] ?? die(Json::GetJsonWarning(1, 'Datos faltantes.'));

        #Verificando los parámetros.
        if (!CircleItem::IsValidName($circle) || !TagItem::IsValidName($name))
            die(Json::GetJsonWarning(2, 'Etiqueta no valida.'));

        try {
            $tag_model = new TagModel();
            $list = $tag_model->Search(Session::GetOnlyRead()->User->ID, $circle, $name);
            unset($tag_model);

            if (empty($list))
                die(Json::GetJsonSuccess(['data' => []]));
            else
                die(Json::GetJsonSuccess(['data' => $list]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }
}