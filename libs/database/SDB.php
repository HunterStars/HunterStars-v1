<?php

	namespace HS\libs\database;

	use HS\config\APP_DB;
	use HS\config\APP_DB_ACCOUNT;
	use HS\config\DBAccount;
	use InvalidArgumentException;
	use PDO;
	use PDOException;
	use PDOStatement;

	class SDB
	{

		/*public static function SelectOnly(PDO $PDO, string $sentence, array $param = null) {
			$result = self::SelectAll($PDO, $sentence, $param);

			if (count($result) === 1)
				if (count($result[0]) === 1)
					return array_values($result[0])[0];
				else
					return $result[0];
			else
				return null;
		}*/


	}