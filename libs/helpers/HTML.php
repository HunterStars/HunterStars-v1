<?php

	namespace HS\libs\helpers;

	use HS\config\enums\SubDomains;
	use HS\libs\io\Path;
	use HS\libs\io\Url;

	class HTML
	{
		public static function Styles(array|string $filenames, SubDomains $domain = null): string {
			$tags = '';
			$filenames = is_string($filenames) ? [$filenames] : $filenames;
			foreach ($filenames as $file)
				$tags .= '<link rel="stylesheet" href="' . UrlFiles::GetCSS($file, $domain) . '">';
			return $tags;
		}

		public static function VendorStyles(array|string $filenames, SubDomains $domain = null): string {
			$tags = '';
			$filenames = is_string($filenames) ? [$filenames] : $filenames;
			foreach ($filenames as $file)
				$tags .= '<link rel="stylesheet" href="' . UrlFiles::GetVendorCSS($file, $domain) . '">';
			return $tags;
		}

		public static function VendorFonts(array|string $filenames, SubDomains $domain = null): string {
			$tags = '';
			$filenames = is_string($filenames) ? [$filenames] : $filenames;
			foreach ($filenames as $file)
				$tags .= '<link rel="stylesheet" href="' . UrlFiles::GetVendorFont($file, $domain) . '">';
			return $tags;
		}

		public static function Scripts(array|string $filenames, SubDomains $domain = null): string {
			$tags = '';
			$filenames = is_string($filenames) ? [$filenames] : $filenames;
			foreach ($filenames as $file)
				$tags .= '<script src="' . UrlFiles::GetJS($file, $domain) . '"></script>';
			return $tags;
		}

        public static function ModuleScripts(array|string $filenames, SubDomains $domain = null): string {
            $tags = '';
            $filenames = is_string($filenames) ? [$filenames] : $filenames;
            foreach ($filenames as $file)
                $tags .= '<script type="module" src="' . UrlFiles::GetJS($file, $domain) . '"></script>';
            return $tags;
        }

		public static function VendorScripts(array|string $filenames, SubDomains $domain = null): string {
			$tags = '';
			$filenames = is_string($filenames) ? [$filenames] : $filenames;
			foreach ($filenames as $file)
				$tags .= '<script src="' . UrlFiles::GetVendorJS($file, $domain) . '"></script>';
			return $tags;
		}
	}