<?php

	namespace HS\libs\helpers;

	class Validator
	{
		public static function IsValid(string $regex, string $text): bool {
			return preg_match('#' . $regex . '#', $text) === 1;
		}

		public static function IsValidUrlPath(string $regex, string $url): bool {
			$url_part = parse_url($url);
			if (count($url_part) == 1 && !empty($url_part['path']))
				return preg_match('#' . $regex . '#', $url) === 1;
			return false;
		}
	}