<?php

	namespace HS\libs\helpers;

	use HS\libs\io\Path;
	use const HS\APP_FILE_MODE;

	class IOUtils
	{
		public static function CreateFile(string $filename): bool {
			//Verificando si el fichero ya existe
			if (is_file($filename)) return true;

			//Si no, creando directorio y el fichero.
			if (IOUtils::CreateDirectory(pathinfo($filename, PATHINFO_DIRNAME), true))
				return file_put_contents($filename, '', FILE_APPEND) !== false;

			return false;
		}

		public static function CreateDirectory(string $path, bool $recursive): bool
		{
			//Verificando si existe, si no crearlo.
			return is_dir($path) || mkdir($path, APP_FILE_MODE, $recursive);
		}

	}