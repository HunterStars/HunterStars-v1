<?php

namespace HS\libs\view;

use HS\config\enums\AppDirs;
use HS\libs\collection\PropertyCollection;
use HS\libs\core\Session;
use HS\libs\helpers\DataUtils;
use HS\libs\helpers\FSLimit;
use HS\libs\helpers\PHPUtils;
use HS\libs\io\Path;
use HS\libs\net\HttpResponse;
use const HS\APP_PATH;

class Template
{
    public static function CallAdmin(string $filename): never
    {
        self::Call(Path::GetViewAdmin(''), $filename);
    }

    public static function CallClient(string $filename): never
    {
        self::Call(Path::GetViewClient(''), $filename);
    }

    private static function Call(string $view_dir, string $filename): never
    {
        //Importando módulos necesarios para vistas.
        PHPUtils::RequireIn('/libs', [
            'helpers/UrlFiles',
            'helpers/UrlMaker',
            'helpers/HTML',
            'helpers/DataUtils',
            'helpers/DateUtils',
            'view/View',
            'view/ViewLayout',
            'view/ViewClientData',
            'exception/PropertyNotFoundException'
        ]);

        //Iniciando sesiones de solo lectura.
        Session::GetOnlyRead();

        //Limitando acceso al directorio de archivos, para permitir acceder a solo las vistas.
        FSLimit::ini_set($view_dir);

        //Obteniendo layout.
        $layout = View::GetLayout();

        //Arreglando ruta de la plantilla y eliminando caracteres no permitidos.
        $filename = Path::Combine($view_dir, str_replace('.', '', $filename) . '.php');

        //Arreglando ruta de las secciones.
        $sections = array_map(fn($sec) => empty($sec) ? '' : Path::Combine($view_dir, str_replace('.', '', $sec) . '.php'), $layout->Sections->GetInnerArray());
        $sections = array_filter($sections, fn($sec) => !empty($sec));
        $layout->Sections = new PropertyCollection($sections);
        unset($sections, $layout, $view_dir);

        //Requiriendo fichero
        if (file_exists($filename))
            require $filename;
        else
            HttpResponse::C404_NOTFOUND->SetCode();
        die;
    }
}