<?php

namespace HS\app\controllers\admin;

use HS\app\models\admin\GroupModel;
use HS\app\models\admin\ProjectModel;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use PDOException;

class GroupController
{
    public function Edit(string $circle, string $project): never
    {
        //Obteniendo parámetro post.
        $title = $_POST['title'];
        $group = $_POST['group'];

        //Validar campo grupo.
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Datos.
            $project_model = new ProjectModel();
            $circle_id = $project_model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $project_model->GetID($user_id, $circle_id, $project, true);

            //Agregando grupo al proyecto.
            $group_model = new GroupModel($project_model);
            if (empty($group))
                $group_id = $group_model->Add($user_id, $circle_id, $project_id, $title);
            else
                $group_id = $group_model->Edit($user_id, $circle_id, $project_id, $group, $title);
            unset($db);

            //Si no se pudo insertar o actualizar, entonces no se tiene acceso al proyecto.
            if (is_null($group_id) || $group_id < 0) {
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                Logger::WriteString(LogFile::NO_ACCESS, "Acceso no autorizado al proyecto \"{$project}\" por \"{$user_id}\"");
                die;
            }

            //Notificar guardado correcto.
            die(Json::GetJsonSuccess(['sort' => empty($group) ? "G-$group_id" : 0]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }
}