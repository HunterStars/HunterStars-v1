<?php

namespace HS\config;

use HS\libs\core\ClassLoader;
use const HS\APP_PATH;

ClassLoader::Register(
    APP_PATH . "/app/controllers",
    APP_PATH . "/app/models",
    APP_PATH . "/libs",
    APP_PATH . "/config/enums",
    APP_PATH . '/vendor/mpdf/mpdf/src',
    APP_PATH . '/vendor/setasign/fpdi/src', //MPDF
    APP_PATH . '/vendor/psr/log/src', //MPDF
    APP_PATH . '/vendor/psr/http-message/src' //MPDF

);
