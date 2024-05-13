<?php

namespace HS\app\controllers\admin;

use Error;
use Exception;
use HS\app\models\admin\CircleModel;
use HS\app\models\admin\EntryModel;
use HS\app\models\admin\ProjectModel;
use HS\app\models\items\CircleItem;
use HS\app\models\items\CircleTypeItem;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\HTMLFilter;
use HS\libs\helpers\Json;
use HS\libs\helpers\Regex;
use HS\libs\io\Url;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use HS\libs\view\Template;
use HS\libs\helpers\Logger;
use HS\libs\view\View;
use PDOException;

class CircleController
{
    public function Index(): never
    {
        try {
            //Obteniendo círculos desde la base de datos.
            $model = new CircleModel();
            $circles = $model->GetList(Session::Get()->User->ID);
            $types = $model->GetTypes();
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Almacenando datos obtenidos en clase estática de la vista.
        $data = View::GetData();
        $data->Circles = $circles;
        $data->CircleTypes = $types;

        //Configurando vista.
        View::GetLayout()
            ->AddSection('main', 'circle/index')
            ->AddStyle('index')
            ->AddScript('c/index')
            ->HideLateralMenu()
            ->HideExtraMenu()
            ->HideNotificationPanel();

        //Llamando a la vista.
        Template::CallAdmin('template');
    }

    public function ViewEdit(string $circle): never
    {
        try {
            //Obteniendo círculos desde la base de datos.
            $model = new CircleModel();
            $circle = $model->Get(Session::GetOnlyRead()->User->ID, $circle);

            //Notificar si no se tiene permiso.
            if (is_null($circle)) {
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            }

            //Obteniendo tipos de círculos.
            $types = $model->GetTypes();
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Almacenando datos obtenidos en clase estática de la vista.
        $data = View::GetData();
        $data->CircleTypes = $types;
        $data->CurrentCircle = $circle;

        //Configurando vista.
        View::GetLayout()
            ->SetTitle('Datos generales')
            ->AddSection('main', 'circle/settings')
            ->AddVendorStyle(['uppy.min', 'choices.min'])
            ->AddVendorScript(['uppy.min', 'uppy.locale.min', 'choices.min', 'ckeditor/ckeditor.inline'])
            ->AddStyle('/vendor/ckeditor/inline')
            ->AddScript(['helper/uppy', 'c/settings']);

        //Llamando a la vista.
        Template::CallAdmin('template');
    }

    public function CreateEditAction(string $circle = null): never
    {
        $title = $_POST['title'];
        $url = $_POST['url'];
        $type = $_POST['type'];
        $desc = $_POST['desc'] ?? '';

        //Validando parámetros.
        if (!Regex::Match(CircleItem::REGEX_TITLE, $title))
            die(Json::GetJsonWarning(1, 'El nombre del circulo no tiene la longitud correcta.'));
        else if (!Regex::Match(CircleItem::REGEX_NAME, $url))
            die(Json::GetJsonWarning(2, 'El enlace no tiene el formato correcto.'));
        else if (filter_var($type, FILTER_VALIDATE_INT) === false)
            die(Json::GetJsonWarning(3, 'El tipo especificado no es valido.'));

        //Validando descripción.
        if (!empty($desc)) {
            try {
                $desc = HTMLFilter::GetHTMLPurifierStringForBasicEditor($desc);
            } catch (Exception|Error $ex) {
                Logger::WriteException(LogFile::HTML_FILTER, $ex);
                die(Json::GetJsonWarning(4, 'La descripción contiene uno o mas textos con formato no valido.'));
            }
        }

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            $model = new CircleModel();
            $types = array_map(fn(CircleTypeItem $item) => $item->ID, $model->GetTypes());

            if (!in_array($type, $types))
                die(Json::GetJsonWarning(5, 'El tipo especificado no existe.'));
            else if (in_array($url, CircleItem::BLACK_LIST))
                die(Json::GetJsonWarning(6, 'El enlace especificado no esta disponible'));
            else if ($circle !== $url && $model->ExistsName($url))
                die(Json::GetJsonWarning(7, 'El enlace especificado ya esta siendo utilizado.'));

            //Insertando datos en base de datos.
            if (is_null($circle))
                $model->Create($user_id, $url, $title, $type);
            else {
                $circle_id = $model->GetID($user_id, $circle);

                //Notificar si no se tiene permiso.
                if (is_null($circle_id)) {
                    Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                    die;
                }

                $model->Edit($user_id, $circle_id, $url, $title, $type, $desc);
            }

            die(json_encode(['success' => true, 'url' => Url::Combine('/' . urlencode($url), 'settings')]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function Dashboard(string $circle): never
    {
        $user_id = Session::GetOnlyRead()->User->ID;
        try {
            //Obteniendo datos del circulo.
            $c_model = new CircleModel();
            $dashboard = $c_model->GetDashboard($user_id, $circle);

            if (is_null($dashboard)) {
                Logger::WriteString(LogFile::NO_ACCESS, "$user_id | No tiene acceso al circulo $circle para ver el dashboard.");
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            }

            $stats_last_30 = $c_model->GetViewsOfLast30Days($user_id, $dashboard->ID);

            //Obteniendo datos de los proyectos del círculo.
            $p_model = new ProjectModel($c_model);
            $top5_projects = $p_model->GetMostPopulars($user_id, $dashboard->ID, 5);
            unset($p_model);

            //Obteniendo datos de los capítulos del circulo.
            $e_model = new EntryModel($c_model);
            $top10_entries = $e_model->GetMostPopularEntries($user_id, $dashboard->ID, 10);
            unset($e_model, $c_model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Datos de la vista.
        $circle = new CircleItem();
        $circle->Name = $dashboard->Name;
        $circle->Title = $dashboard->Title;

        $data = View::GetData();
        $data->CurrentCircle = $circle;
        $data->Dashboard = $dashboard;
        $data->ViewsOfLast30Day = $stats_last_30;
        $data->Top5Projects = $top5_projects;
        $data->Top10Entries = $top10_entries;

        //Configurando vista.
        View::GetLayout()
            ->AddSection('main', 'circle/dashboard')
            ->AddVendorStyle('uppy.min')
            ->AddVendorScript(['uppy.min', 'uppy.locale.min', 'apexcharts.min'])
            ->AddScript('c/dashboard');

        Template::CallAdmin('template');
    }
}