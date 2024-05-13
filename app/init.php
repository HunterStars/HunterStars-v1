<?php

namespace HS;

use HS\config\enums\AppDirs;
use HS\libs\helpers\PHPUtils;

#Autocarga de clases.
require APP_PATH . "/libs/core/ClassLoader.php";
require APP_PATH . "/config/autoload.php";

#Habilitando sistema de logs.
require APP_PATH . "/app/.init/Logs.php";

#Configuración.
PHPUtils::RequireIn(AppDirs::CONFIG, [
    'db',
    'scss',
    'log'
]);

#Enums importantes.
PHPUtils::RequireIn(AppDirs::ENUMS, [
    'UploadImageDir'
]);

#Rutas.
PHPUtils::RequireIn(AppDirs::ROUTES, [
    'core',
    'image',
    'web',
    'subdomain/main',
    'subdomain/studio'
]);


