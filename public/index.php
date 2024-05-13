<?php

namespace HS {
    #Depuración.
    const APP_DEBUG = false;

    #Aplicación.
    /**Espacio de nombres base de la aplicación.*/
    const APP_NAMESPACE = __NAMESPACE__;

    /**Ruta del directorio raiz de la aplicación.*/
    define(__NAMESPACE__ . "\APP_PATH", realpath(__DIR__ . "/.."));

    /**Url raiz de la aplicación.*/
    define(__NAMESPACE__ . '\APP_URL', pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));

    /**Permiso a nivel de usuario para archivos y carpetas que crea la aplicación.*/
    const APP_FILE_MODE = 0770;
    const APP_FILE_MODE_UMASK = 0002;
}

namespace {

    use HS\config\enums\SubDomains;
    use HS\libs\helpers\FSLimit;
    use HS\libs\net\Http;
    use HS\libs\net\HttpStatus;
    use const HS\APP_DEBUG;
    use const HS\APP_FILE_MODE_UMASK;
    use const HS\APP_PATH;

    require_once '../libs/helpers/OperatingSystem.php';
    require_once '../libs/helpers/FSLimit.php';
    require_once '../config/enums/SubDomains.php';

    #Si tiene cookies de depuración y no se está en modo de depuración.
    if (!APP_DEBUG && isset($_COOKIE['XDEBUG_SESSION'])) {
        Http::SetResponseCode(HttpStatus::C403_FORBIDDEN);
        die;
    }

    #Advertencias y reportes de errores.
    error_reporting(APP_DEBUG ? E_ALL : 0);
    ini_set('display_errors', APP_DEBUG ? 1 : 0);

    //Manejo de cookies de sesión.
    ini_set("session.use_trans_sid", "0");
    ini_set("session.use_only_cookies", "1");

    //Establecer que la cookie de sesión sea válida en todos los subdominios.
    ini_set('session.cookie_domain', '.' . SubDomains::root->value);

    //Estableciendo el nombre de la cookie de sesión.
    session_name('HSS');

    #Limitando acceso a ficheros del servidor.
    FSLimit::ini_set(APP_PATH);

    #Estableciendo mascará de permisos.
    umask(APP_FILE_MODE_UMASK);

    #Iniciando aplicación.
    require '../app/init.php';

    #Si no se encontró una ruta valida.
    http_response_code(404);
    //header("Location: /404");
}
