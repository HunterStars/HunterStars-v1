<?php

namespace HS\app\controllers\admin;

use Error;
use Exception;
use HS\app\models\admin\CircleModel;
use HS\app\models\admin\EntryGroupModel;
use HS\app\models\admin\EntryModel;
use HS\app\models\admin\ProjectModel;
use HS\app\models\admin\TagModel;
use HS\app\models\items\ProjectItem;
use HS\app\models\items\TagItem;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\HTMLFilter;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\MimeType;
use HS\libs\io\Url;
use HS\libs\net\Http;
use HS\libs\net\HttpResponse;
use HS\libs\net\HttpStatus;
use HS\libs\view\Template;
use HS\libs\view\View;
use PDOException;

class ProjectController
{
    public function IndexView(string $circle): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos del circulo.
            $model = new ProjectModel();
            $c_model = new CircleModel($model);
            $circle_obj = $c_model->GetBasic($user_id, $circle, true);
            unset($c_model);

            //Obteniendo datos de los proyectos del círculo.
            $projects = $model->GetList($user_id, $circle_obj->ID);
            $projects_types = $model->GetStatusCatalog($circle_obj->TypeID);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Almacenando datos obtenidos en clase estática de la vista.
        $viewData = View::GetData();
        $viewData->Projects = $projects;
        $viewData->ProjectTypes = $projects_types;
        $viewData->CurrentCircle = $circle_obj;

        #Configurando vista.
        View::GetLayout()
            ->AddSection('main', 'projects/index')
            ->AddScript('p/index');

        //Llamando a la vista.
        Template::CallAdmin('template');
    }

    public function Add(string $circle): never
    {
        //Validando parámetros.
        $this->ValidateProjectParams();

        $title = $_POST['title'];
        $title_alt = $_POST['title_alt'] ?? '';
        $url = $_POST['url'];
        $state = $_POST['state'];

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos del circulo.
            $model = new ProjectModel();
            $c_model = new CircleModel($model);
            $circle_obj = $c_model->GetBasic($user_id, $circle, true);
            unset($c_model);

            //Verificando que el enlace sea único.
            if ($model->ExistsUrl($url))
                die(Json::GetJsonWarning(5, 'El enlace especificado ya esta siendo utilizado.'));

            //Insertando datos en base de datos.
            if (!$model->Add($user_id, $circle_obj->ID, $title, $title_alt, $url, $state))
                die(Json::GetJsonWarning(6, 'No fue posible guardar los datos.'));

            die(Json::GetJsonSuccess(['url' => htmlspecialchars($url)]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function EditView(string $circle, string $project): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos del circulo.
            $model = new ProjectModel();
            $c_model = new CircleModel($model);
            $circle_obj = $c_model->GetBasic($user_id, $circle, true);
            unset($c_model);

            //Obteniendo datos del proyecto.
            $project = $model->Get($user_id, $circle_obj->ID, $project, true);
            $categories = $model->GetCategories($user_id, $circle_obj->ID, $project->ID);
            $projects_types = $model->GetStatusCatalog($circle_obj->TypeID);

            //Obteniendo capítulos del proyecto.
            $e_model = new EntryModel($model);
            $entries = $e_model->GetList($user_id, $circle_obj->ID, $project->ID);
            unset($e_model);

            //Obteniendo grupos.
            $g_model = new EntryGroupModel($model);
            $groups = $g_model->GetList($user_id, $circle_obj->ID, $project->ID);
            unset($g_model, $model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Almacenando datos obtenidos en clase estática de la vista.
        $viewData = View::GetData();
        $viewData->Project = $project;
        $viewData->Project->Categories = $categories;
        $viewData->ProjectTypes = $projects_types;
        $viewData->Groups = $groups;
        $viewData->Entries = $entries;
        $viewData->CurrentCircle = $circle_obj;

        //Configurando vista.
        View::GetLayout()
            ->AddSection('main', 'projects/item')
            ->AddVendorStyle(['uppy.min', 'choices.min', 'tagify'])
            ->AddVendorScript(['uppy.min', 'uppy.locale.min', 'choices.min', 'tagify', 'ckeditor/ckeditor.inline'])
            ->AddVendorScript(['Sortable.min'])
            ->AddStyle('pages/add')
            ->AddScript(['helper/uppy', 'p/item']);

        //Llamando a la vista.
        Template::CallAdmin('template');
    }

    public function EditAction(string $circle, string $project): never
    {
        //Estableciendo tipo de respuesta.
        HttpResponse::SetContentType(MimeType::Json);

        #Verificando parámetros pasados por POST.
        $this->ValidateProjectParams();

        #Validando categorías.
        $categories_json = json_decode($_POST['categories']);
        if (is_null($categories_json))
            die(Json::GetJsonWarning(5, 'El campo categoría tiene un formato incorrecto.'));
        $categories = array_filter($categories_json, fn($tag) => (!empty($tag->code) && preg_match('#' . TagItem::REGEX_ID . '#', $tag->code) === 1)
            and $tag->__isValid === true and preg_match('#' . TagItem::REGEX_NAME . '#', $tag->value) === 1);
        if (count($categories_json) != count($categories))
            die(Json::GetJsonWarning(6, 'Una o mas categorías no son validas.'));
        unset($categories_json);

        #Validando sinopsis.
        try {
            $synopsis = HTMLFilter::GetHTMLPurifierStringForBasicEditor($_POST['synopsis'] ?? '');
        } catch (Exception|Error $ex) {
            Logger::WriteException(LogFile::HTML_FILTER, $ex);
            die(Json::GetJsonWarning(7, 'La sinopsis contiene uno o mas textos con formato no valido.'));
        }

        #Extrayendo parámetros verificados.
        $title = $_POST['title'];
        $title_alt = $_POST['title_alt'] ?? '';
        $url = $_POST['url'];
        $state = $_POST['state'];
        $categories = array_map(fn($x) => $x->code, $categories);

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Creando modelos.
            $project_model = new ProjectModel();
            $tag_model = new TagModel($project_model);

            //Obteniendo ID del círculo y del proyecto.
            $circle_id = $project_model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $project_model->GetID($user_id, $circle_id, $project, true);

            //Verificando que el enlace sea único.
            if ($project != $url && $project_model->ExistsUrl($url))
                die(Json::GetJsonWarning(8, 'El enlace especificado ya esta siendo utilizado.'));

            //Guardando datos generales.
            if (!$project_model->Edit($user_id, $circle_id, $project_id, $title, $title_alt, $url, $state, $synopsis))
                die(Json::GetJsonWarning(9, 'No fue posible guardar los datos.'));

            //Insertando categorías.
            if (!$tag_model->InsertAll($user_id, $circle_id, $project_id, $categories))
                die(Json::GetJsonWarning(10, 'No fue posible guardar las categorías.'));

            unset($tag_model, $project_model);

            //Informando éxito.
            die(Json::GetJsonSuccess(['url' => htmlspecialchars($url)]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function ChapterView(string $circle, string $project, ?string $chapter = null): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos del circulo.
            $model = new ProjectModel();
            $c_model = new CircleModel($model);
            $circle_obj = $c_model->GetBasic($user_id, $circle, true);
            unset($c_model);

            //Obteniendo datos del proyecto.
            $project_obj = $model->Get($user_id, $circle_obj->ID, $project, true);
            unset($model);

            //Obteniendo capítulo.
            if (!is_null($chapter)) {
                $model = new EntryModel();
                $chapter_obj = $model->Get($user_id, $circle_obj->ID, $project_obj->ID, $chapter, true);
                unset($model);
            }
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Almacenando datos obtenidos en clase estática de la vista.
        $viewData = View::GetData();
        $viewData->Entry = $chapter_obj ?? null;
        $viewData->CurrentCircle = $circle_obj;

        //Configurando vista.
        View::GetLayout()
            ->AddSection('main', 'projects/chapter')
            ->AddVendorScript(['ckeditor/ckeditor.document'])
            ->AddStyle('pages/chapter')
            ->AddScript('p/chapter');

        //Llamando a la vista.
        Template::CallAdmin('template');
    }

    public function ChapterAction(string $circle, string $project, ?string $chapter = null): never
    {
        //Estableciendo tipo de respuesta.
        HttpResponse::SetContentType(MimeType::Json);

        //Validando capítulo.
        try {
            $title = htmlspecialchars_decode(HTMLFilter::PurifyTitleOfDocumentEditor($_POST['title'] ?? ''));
            $content = HTMLFilter::PurifyBodyOfDocumentEditor($_POST['content'] ?? '');
        } catch (Exception|Error $ex) {
            Logger::WriteException(LogFile::HTML_FILTER, $ex);
            die(Json::GetJsonWarning(2, 'El capítulo contiene uno o mas textos con formato no valido.'));
        }

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo IDs del círculo y del proyecto.
            $model = new ProjectModel();
            $circle_id = $model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $model->GetID($user_id, $circle_id, $project, true);
            unset($model);

            //Agregando capítulo del proyecto.
            $model = new EntryModel();
            if (is_null($chapter))
                $entry_url = $model->Add($user_id, $circle_id, $project_id, $title, $content);
            else
                $entry_url = $model->Edit($user_id, $circle_id, $project_id, $chapter, $title, $content);
            unset($model);

            //Si no se pudo insertar, entonces no se tiene acceso al proyecto.
            //Aunque... puedo ser que se deba a que ya existe el Name, pero eso se valida antes asi que... No.
            if (empty($entry_url)) {
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                Logger::WriteString(LogFile::NO_ACCESS, "Acceso no autorizado al proyecto \"{$project}\" por \"{$user_id}\"");
                die;
            }

            //Notificar guardado correcto.
            die(Json::GetJsonSuccess(is_null($chapter) ? ['url' => $entry_url] : []));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function SortEntryAndGroups(string $circle, string $project): never
    {
        //Verificando el formato del json recibido.
        $json = $_POST['sort'];
        $json_pattern = '#^\[(("[\w\-]+"|\{"G-\d+":\[(("[\w\-]+"),?)*]}),?)*]$#';
        if (preg_match($json_pattern, $json) !== 1) {
            Logger::WriteString(LogFile::GET_POST_FORMAT, "pattern: $json_pattern | json: $json");
            die(Json::GetJsonWarning(1, "Formato de ordenado no valido"));
        }

        //Transformando json.
        $sorted_tree = json_decode($json);
        if (is_null($json)) {
            Logger::WriteString(LogFile::GET_POST_FORMAT, "json_encode: fail | json: $json");
            die(Json::GetJsonWarning(2, "Formato de ordenado no valido"));
        }
        unset($json_pattern, $json);

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos.
            $model = new ProjectModel();
            $circle_id = $model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $model->GetID($user_id, $circle_id, $project, true);

            //Guardando ordenamiento.
            $model = new ProjectModel();
            if (!$model->SaveSort($user_id, $circle_id, $project_id, $sorted_tree))
                die(Json::GetJsonWarning(3, 'No fue posible guardar los datos de ordenado.'));
            unset($model);

            //Informando éxito.
            die(Json::GetJsonSuccess());
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    private function ValidateProjectParams(): void
    {
        //Validando que estén los parámetros necesarios.
        if (empty($_POST['title']) || empty($_POST['url']) || empty($_POST['state']))
            die(Json::GetJsonWarning(1, 'Uno o mas datos requeridos están vacíos'));

        #Asignando parámetros requeridos.
        $url = str_replace('/', '', Url::Trim($_POST['url']));
        $state = $_POST['state'];

        //Validando parámetros.
        if (!ProjectItem::IsValidTitle($_POST['title']) ||
            (!empty($_POST['title_alt']) && !ProjectItem::IsValidTitle($_POST['title_alt'])))
            die(Json::GetJsonWarning(2, 'Titulo o titulo alternativo no valido.'));

        if (!ProjectItem::IsValidUrl($url))
            die(Json::GetJsonWarning(3, 'El enlace proporcionado no es valido.'));

        if (filter_var($state, FILTER_VALIDATE_INT) === false)
            die(Json::GetJsonWarning(4, 'El estado proporcionado no es valido'));

        //Limpiando parámetros.
        $_POST['url'] = urlencode(filter_var($url, FILTER_SANITIZE_URL));
        $_POST['state'] = filter_var($state, FILTER_SANITIZE_NUMBER_INT);
    }
}
