<?php

	namespace HS\libs\core;

	use DirectoryIterator;
    use HS\libs\io\Path;
    use InvalidArgumentException;
	use const HS\APP_NAMESPACE;
	use const HS\APP_PATH;

	#Registrando como función para autoincludes.
	spl_autoload_register(function (string $class) {
		ClassLoader::Import($class);

		//Si la clase lo tiene, se llama al constructor estatico.
		if (method_exists($class, '_st_init'))
			$class::_st_init();
	});

	class ClassLoader
	{
		private static array $List = [];

		public static function Register(): void {
			foreach (func_get_args() as $path) {
				if (!is_string($path))
					throw new InvalidArgumentException("Se esperaba una cadena especificando un directorio para buscar clases.");
				else if (!str_starts_with($path, APP_PATH))
					throw new InvalidArgumentException('No se permite directorios ubicados fuera de la aplicación.');
				else if (!is_dir($path))
					throw new InvalidArgumentException('La ruta especificada no existe o no es un directorio.');

				self::$List[] = realpath($path);
			}
		}

		public static function Import(string $class): void {
			//Si la clase pertenece al espacio de nombres principal,
			//tratar de incluir resolviendo directamente su ruta.
			if (str_starts_with($class, APP_NAMESPACE . "\\")){
				$fileClass = substr_replace($class, APP_PATH, 0, strlen(APP_NAMESPACE));
                $fileClass = str_replace(DIRECTORY_SEPARATOR == '/' ? '\\' : '/', DIRECTORY_SEPARATOR, $fileClass);
                $fileClass = realpath("$fileClass.php");

				//Si el directorio de la clase está en la lista y existe el archivo de la clase.
				foreach (self::$List as $directory){
					if (str_starts_with($fileClass, $directory)){
						if (file_exists($fileClass)) {
							require_once $fileClass;

							if (class_exists($class)) return;
							else break;
						}
					}
				}
			}

			//Si no pertenece al principal, o no posee un nombre cualificado,
			//se busca en cada directorio y subdirectorio de la lista,
			//incluyendo todas las clases del mismo nombre.
			foreach (self::$List as $directory)
				self::Search($class, $directory);
		}

		private static function Search(string $class, string $directory): void {
			$file = pathinfo(Path::Trim("$class.php"), PATHINFO_BASENAME);

			foreach (new DirectoryIterator($directory) as $fileinfo) {
				if (!$fileinfo->isDot()) {
					if ($fileinfo->isDir())
						self::Search($class, $fileinfo->getRealPath());
					else if ($fileinfo->getFilename() == $file)
						require_once $fileinfo->getPathname();
				}
			}
		}
	}