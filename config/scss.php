<?php

namespace HS\config;

use HS\config\enums\AppDirs;
use HS\libs\io\Path;
use const HS\APP_DEBUG;

/** Determina si los ficheros .css resultantes estarán comprimidos. <br/><br/>
 * <b>Nota:</b> Es necesario limpiar la cache para observar los cambios.</i> */
const SCSS_COMPRESS = !APP_DEBUG;

/** Ruta al directorio donde se guardara la caché de los ficheros .scss ya procesados. <br/>
 * Ejemplo: /<<PATH_ROOT>>/.temp/cache/scss */
define(__NAMESPACE__ . "\SCSS_PATH_CACHE", Path::CombineRoot(AppDirs::CACHE, '/scss'));

/** Pseudo-ruta en la que el navegador ubicara los ficheros .scss obtenidos mediante el SourceMap.*/
const SCSS_PATH_SOURCE = '/files/';