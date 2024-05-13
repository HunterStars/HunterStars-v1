<?php

namespace HS\app\controllers\core;

use HS\app\models\admin\CircleModel;
use HS\app\models\admin\image\GroupCoverImageModel;
use HS\app\models\admin\image\ImageModel;
use HS\app\models\admin\image\ProjectCoverImageModel;
use HS\app\models\admin\ProjectModel;
use HS\app\models\client\NovelModel;
use HS\config\enums\AppDirs;
use HS\config\enums\AppImageDirs;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\AppFiles;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\MimeType;
use HS\libs\helpers\Random;
use HS\libs\helpers\UrlFiles;
use HS\libs\io\Path;
use HS\libs\io\Url;
use HS\libs\media\Image;
use HS\libs\media\ImageException;
use HS\libs\net\Http;
use HS\libs\net\HttpResponse;
use HS\libs\net\HttpStatus;
use PDOException;

class ImageController
{
    public static function Get(string $type, string $filename): never
    {
        //Localizando el tipo de imagen y obteniendo ruta.
        $path = self::GetPathOfType($type, $filename);

        //Convirtiendo archivo php en imagen.
        HttpResponse::SetContentType(MimeType::OfFile($path));

        //Imprimiendo imagen.
        self::PrintImage($path, $type, isset($_GET['w']) || isset($_GET['h']));
        die;
    }

    #Admin Gets.
    public static function GetAdminCircle($circle, $filename): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo covers del círculo.
            $model = new CircleModel();
            $covers = $model->GetCovers($user_id, $circle);
            unset($model);

            if (is_null($covers)) {
                Logger::WriteString(LogFile::NO_ACCESS, "$user_id no tiene permiso para acceder a las imágenes del circulo $circle");
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            }
        } catch (PDOException $ex) {
            Http::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        if ($filename === $covers->CoverImg || $filename === $covers->ProfileImg)
            self::PrintImage(AppFiles::GetUploadIMG($covers->ID, '', $filename), 'circle',
                isset($_GET['w']) || isset($_GET['h']));
        else
            Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
        die;
    }

    public static function GetAdminProject(string $circle, string $project, string $filename): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo IDs.
            $model = new ProjectModel();
            $circle_id = $model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $model->GetID($user_id, $circle_id, $project, true);
            unset($model);
        } catch (PDOException $ex) {
            HttpResponse::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        self::PrintImage(AppFiles::GetUploadIMG($circle_id, $project_id, $filename), 'project',
            isset($_GET['w']) || isset($_GET['h']));
        die;
    }

    #Client Gets.
    public static function GetChapter(string $project, string $filename): never
    {
        try {
            $model = new NovelModel();
            $project = $model->Get($project, null);
            //$novel = $model->GetIDs($project);
            unset($model);

            if (empty($project)) {
                HttpResponse::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
                HttpResponse::C404_NOTFOUND->SetCode();
                die;
            }
        } catch (PDOException $ex) {
            HttpResponse::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        self::PrintImage(AppFiles::GetUploadIMG($project->CircleID, $project->ID, $filename), 'chapter',
            isset($_GET['w']) || isset($_GET['h']));
        die;
    }

    public static function GetProject(string $project, string $filename): never
    {
        try {
            //Verificando que la imagen exista en el proyecto.
            $model = new NovelModel();
            $image = $model->GetImage($project, $filename);
            unset($model);

            if (empty($image)) {
                Http::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }
        } catch (PDOException $ex) {
            HttpResponse::SetContentType(MimeType::OfExtension(pathinfo($filename, PATHINFO_EXTENSION)));
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        self::PrintImage(AppFiles::GetUploadIMG($image->CircleID, $image->ProjectID, $filename), 'project',
            isset($_GET['w']) || isset($_GET['h']));
        die;
    }

    #Uploads
    public static function UploadDB(ImageModel $model, array $file, string $base_path, string $new_url): never
    {
        //Obteniendo nuevo nombre de la imagen.
        $new_name = Random::GetTextID() . '.' . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_file = Path::Combine($base_path, $new_name);

        //Moviendo imagen subida a directorio de imágenes.
        Image::HTTPUploadTo($file, $base_path, $new_name);

        try {
            //Obteniendo imagen actual.
            $current_cover = $model->Get();

            //Si no se obtuvieron datos, o no se pudo actualizar notificar que no se tiene permiso para obtenerlos.
            if (is_null($current_cover) || !$model->Edit($new_name)) {
                //Eliminando nuevo archivo.
                unlink($new_file);

                //Notificando error.
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            } else {
                //Eliminando viejo archivo si existe.
                if (!empty($current_cover)) {
                    $old_file = Path::Combine($base_path, $current_cover);
                    if (file_exists($old_file))
                        unlink($old_file);
                }

                //Devolviendo respuesta.
                die(Json::GetJsonSuccess(['url' => htmlspecialchars(Url::Combine($new_url, $new_name))]));
            }
        } catch (PDOException $ex) {
            //Eliminando nuevo archivo.
            unlink($new_file);

            //Notificando error.
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function UploadAdminProject(string $circle, string $project): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo IDs.
            $project_model = new ProjectModel();
            $circle_id = $project_model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $project_model->GetID($user_id, $circle_id, $project, true);
            unset($project_model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando path y url de fichero.
        $new_path = AppFiles::GetUploadIMG($circle_id, $project_id, '');
        $new_url = UrlFiles::GetProjectAdminIMG($circle, $project, '');

        //Si la imagen se subió correctamente.... Actualizar BD.
        self::UploadDB(new ProjectCoverImageModel($circle_id, $project_id), $_FILES['file'], $new_path, $new_url);
    }

    public function UploadAdminGroup(string $circle, string $project): never
    {
        //Obteniendo id del grupo.
        $group_id = filter_var(str_replace('G-', '', $_POST['filename']), FILTER_SANITIZE_NUMBER_INT);

        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo IDs.
            $project_model = new ProjectModel();
            $circle_id = $project_model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $project_model->GetID($user_id, $circle_id, $project, true);
            unset($project_model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando path y url de fichero.
        $new_path = AppFiles::GetUploadIMG($circle_id, $project_id, '');
        $new_url = UrlFiles::GetProjectAdminIMG($circle, $project, '');

        //Si la imagen se subió correctamente.... Actualizar BD.
        self::UploadDB(new GroupCoverImageModel($circle_id, $project_id, $group_id), $_FILES['file'], $new_path, $new_url);
    }

    public static function UploadCircle(string $circle, string $type): never
    {
        $user_id = Session::GetOnlyRead()->User->ID;

        try {
            $model = new CircleModel();
            $covers = $model->GetCovers($user_id, $circle);

            if (is_null($covers)) {
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            }
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando nuevo nombre de fichero.
        $file = $_FILES['file'];
        $new_name = Random::GetTextID() . '.' . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_path = AppFiles::GetUploadIMG($covers->ID, '', '');

        //Moviendo imagen subida a directorio de portadas.
        Image::HTTPUploadTo($file, $new_path, $new_name);

        //Obteniendo ruta de la nueva imagen.
        $new_path = Path::Combine($new_path, $new_name);

        //Actualizando base de datos.
        try {
            $model = new CircleModel();
            $result = match ($type) {
                'cover' => $model->SetCover($user_id, $covers->ID, $new_name),
                'profile' => $model->SetProfile($user_id, $covers->ID, $new_name),
                default => false
            };

            if (!$result) {
                unset($model);

                //Eliminando nuevo archivo.
                unlink($new_path);

                //Notificando error.
                Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
                die;
            }
            unset($model);
        } catch (PDOException $ex) {
            //Eliminando nuevo archivo.
            unlink($new_path);

            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Eliminando viejo archivo si existe.
        $old_file = match ($type) {
            'cover' => $covers->CoverImg,
            'profile' => $covers->ProfileImg,
            default => ''
        };
        if (!empty($old_file)) {
            $old_file = AppFiles::GetUploadIMG($covers->ID, '', $old_file);
            if (file_exists($old_file))
                unlink($old_file);
        }

        //Devolviendo respuesta.
        die(json_encode(['success' => true, 'url' => Url::Combine('cover', urlencode($new_name))]));
    }

    public static function UploadChapter(string $circle, string $project): never
    {
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo datos.
            $model = new ProjectModel();
            $circle_id = $model->GetCircleID($user_id, $circle, $project, true);
            $project_id = $model->GetID($user_id, $circle_id, $project, true);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando nuevo nombre de fichero.
        $file = $_FILES['upload'];
        $new_name = Random::GetTextID() . '.' . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_path = AppFiles::GetUploadIMG($circle_id, $project_id, '');

        //Moviendo imagen subida a directorio de portadas.
        Image::HTTPUploadTo($file, $new_path, $new_name);

        //Devolviendo respuesta.
        $url = htmlspecialchars(Url::Combine('img', urlencode($new_name)));
        $urls = [
            "default" => $url,
            "320" => $url . "?w=320",
            "425" => $url . "?w=425",
            "800" => $url . "?w=800",
            "1024" => $url . "?w=1024",
            "1920" => $url . "?w=1920"
        ];
        die(Json::GetJsonSuccess(['urls' => $urls]));
    }

    private static function PrintImage(string $path, string $cache_path, bool $needThumb): void
    {
        //Obteniendo parámetros proporcionados por GET.
        $THUMB_WIDTH = intval($_GET['w'] ?? 0);
        $THUMB_HEIGHT = intval($_GET['h'] ?? 0);

        //Verificando que la imagen exista.
        if (!file_exists($path)) {
            HttpResponse::SetContentType(MimeType::OfExtension(pathinfo($path, PATHINFO_EXTENSION)));
            HttpResponse::C404_NOTFOUND->SetCode();
            die;
        }

        //Convirtiendo archivo php en imagen.
        HttpResponse::SetContentType(MimeType::OfFile($path));

        try {
            //Abriendo imagen original
            $image = Image::FromFile($path);

            if (!$needThumb) {
                $image->Print();
                unset($image);
                die;
            }
        } catch (ImageException $ex) {
            if ($ex->getCode() == ImageException::NOT_FOUND) {
                HttpResponse::C404_NOTFOUND->SetCode();
                die;
            } else
                Logger::SetInternalErrorAndLog(LogFile::IMG, $ex);
        }

        //Identificando la última fecha de modificación de la imagen original.
        if (($time = filemtime($path)) !== false)
            HttpResponse::SetHeader('Last-Modified', gmdate('D, d M Y H:i:s', $time) . ' GMT');

        //Obteniendo miniatura e imprimiéndola.
        try {
            $thumb = $image->GetThumbnail($THUMB_WIDTH, $THUMB_HEIGHT, Path::CombineRoot(AppDirs::IMAGE_CACHE, $cache_path));
            $thumb->Print();
        } catch (ImageException $ex) {
            if ($ex->getCode() != ImageException::NOT_FOUND) {
                Logger::WriteException(LogFile::IMG, $ex);
            }

            try {
                //Sino, devolver imagen original
                $image->Print();
            } catch (ImageException $err) {
                Logger::SetInternalErrorAndLog(LogFile::IMG, $err);
            }
        }

        //Cerrando recursos
        unset($image);
        unset($thumb);
        die;
    }

    private static function GetPathOfType(string $type, string $filename): ?string
    {
        //Obteniendo el tipo de imagen.
        $type = AppImageDirs::tryFrom($type);

        //Si no es un tipo de imagen válido...
        if ($type === null) {
            HttpResponse::C404_NOTFOUND->SetCode();
            die;
        }

        //Localizando el tipo de imagen y obteniendo ruta.
        return Path::CombineRoot(AppDirs::IMAGES, $type, $filename);
    }
}