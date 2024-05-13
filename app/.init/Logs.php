<?php

	namespace HS\app\init;

	use HS\config\enums\AppDirs;
	use HS\libs\helpers\IOUtils;
	use HS\libs\io\Path;

	//Habilitando log de errores.
	ini_set('log_errors', 1);

	//Creando fichero de log.
	$filename = Path::CombineRoot(AppDirs::LOG, "core.log");
	if (IOUtils::CreateFile($filename)) ini_set('error_log', $filename);