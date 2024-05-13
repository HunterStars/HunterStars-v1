<?php

	namespace HS\libs\helpers;

	use HS\config\enums\AppDirs;
	use HS\libs\io\Path;

	class PHPUtils
	{
		public static function RequireIn(string|AppDirs $path, array $files): void {
			foreach ($files as $file)
				require_once Path::CombineRoot($path, "$file.php");
		}
	}