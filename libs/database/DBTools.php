<?php

	namespace HS\libs\database;

	use HS\config\APP_DB;
	use HS\config\DBAccount;
	use PDO;
	use PDOStatement;

	class DBTools
	{
		public static function Connect(DBAccount $account): PDO {
			//Obteniendo datos de la cuenta de la DB.
			$account = $account->name;
			$account_data = APP_DB::ACCOUNTS[$account];

			//Cadena de conexión.
			$dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", APP_DB::HOST, APP_DB::NAME, APP_DB::CHARSET);

			//Devolviendo objeto de la conexión.
			return new PDO($dsn, $account_data[0], $account_data[1], [
				PDO::ATTR_EMULATE_PREPARES => false, //Desactivar virtualización de consultas preparadas.
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Activar las excepciones.
			]);
		}

		public static function BindValue(PDOStatement $prepare, array $params): void {
			foreach ($params as $key => $value)
				$prepare->bindValue(is_int($key) ? ++$key : $key, $value, self::GetPDOType($value));
		}

		private static function GetPDOType($var): int {
			if (is_bool($var)) return PDO::PARAM_BOOL;
			else if (is_null($var)) return PDO::PARAM_NULL;
			else if (is_int($var)) return PDO::PARAM_INT;
			else return PDO::PARAM_STR;
		}
	}