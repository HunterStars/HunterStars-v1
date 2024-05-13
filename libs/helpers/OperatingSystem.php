<?php
	namespace HS\libs\helpers;

	enum OperatingSystem
	{
		case WIN;
		case LINUX;
		case OSX;
		case SOLARIS;
		case UNKNOWN;

		public static function GetCurrent() : OperatingSystem{
			return match (true) {
				is_string(stristr(PHP_OS_FAMILY, 'WIN')) => self::WIN,
				is_string(stristr(PHP_OS_FAMILY, 'LIN')) => self::LINUX,
				is_string(stristr(PHP_OS_FAMILY, 'OSX')) => self::OSX,
				is_string(stristr(PHP_OS_FAMILY, 'SOL')) => self::SOLARIS,
				default => self::UNKNOWN,
			};
		}
	}