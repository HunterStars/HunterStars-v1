<?php

	namespace HS\app\models\items;

	use HS\libs\helpers\Regex;

	class TagItem
	{
		const REGEX_ID = Regex::UNSIGNED_INT;
		const REGEX_NAME = '^[\wáéíóúÁÉÍÓÚñ ]+$';

		public int $ID;
		public int $Type;
		public string $Name;

		public static function IsValidName(string $name, bool $empty = false): bool {
			return preg_match('#' . ($empty ? '^$|' : '') . self::REGEX_NAME . '#', $name) === 1;
		}
	}