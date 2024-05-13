<?php

namespace HS\libs\database;

use HS\config\APP_DB;
use HS\config\DBAccount;
use HS\config\LogFile;
use HS\libs\collection\PropertyCollection;
use HS\libs\helpers\Logger;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private PDO $PDO;

    public function __construct(PDO|DBAccount|DB $account)
    {
        if (is_a($account, DBAccount::class))
            $this->PDO = DBTools::Connect($account);
        else if (is_a($account, DB::class))
            $this->PDO = $account->PDO;
        else
            $this->PDO = $account;
    }

    public function __destruct()
    {
        unset($this->PDO);
    }

    public function GetPDO(): PDO
    {
        return $this->PDO;
    }

    public function Run(string $sentence, array $params = null): PDOStatement
    {
        //Preparando consulta.
        $sentence = $this->PDO->prepare($sentence);

        //Vinculando parámetros de la consulta.
        if (!empty($params))
            DBTools::BindValue($sentence, $params);

        //Ejecutando consulta.
        $result = $sentence->execute();

        //Devolviendo resultados.
        return $sentence; //Devuelve PDOStatement.
    }

    public function SelectAll(string $sentence, array $params = null, string $class = null): array
    {
        //Preparando y ejecutando consulta
        $sentence = $this->Run($sentence, $params);

        if (is_null($class))
            $result = $sentence->fetchAll(PDO::FETCH_OBJ);
        else
            $result = $sentence->fetchAll(PDO::FETCH_CLASS, $class);
        $sentence->closeCursor();

        return $result !== false ? $result : [];
    }

    public function SelectAllGroupBy(string $sentence, array $params = null, string $class = null): array
    {
        //Preparando y ejecutando consulta
        $sentence = $this->Run($sentence, $params);

        if (is_null($class))
            $result = $sentence->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_GROUP);
        else
            $result = $sentence->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_GROUP, $class);
        $sentence->closeCursor();

        return $result !== false ? $result : [];
    }

    public function SelectOnly(string $sentence, array $params = null, string $class = null): mixed
    {
        //Preparando y ejecutando consulta
        $sentence = $this->Run($sentence, $params);

        //Obteniendo resultado.
        if (is_null($class))
            $data = $sentence->fetchAll(PDO::FETCH_ASSOC);
        else
            $data = $sentence->fetchAll(PDO::FETCH_CLASS, $class);

        //Cerrando cursor.
        $sentence->closeCursor();

        //Devolviendo resultado.
        if (empty($data)) return is_null($class) ? [] : null;

        if (is_null($class))
            return count($data[0]) == 1 ? $data[0][array_key_first($data[0])] : new PropertyCollection($data[0]);
        else
            return count($data) == 1 ? $data[0] : null;
    }

    public function SelectEach(string $sentence, array $params, callable $action): void
    {
        //Preparando y ejecutando consulta
        $sentence = $this->Run($sentence, $params);

        //Recorriendo cada fila de resultados.
        while ($row = $sentence->fetch(PDO::FETCH_OBJ))
            //Pasando al callback la fila obtenida.
            $action($row);

        //Cerrando cursor.
        $sentence->closeCursor();
    }

    public function Transaction(callable $actions): bool
    {
        $this->PDO->beginTransaction();

        try {
            if ($actions() === false) {
                $this->PDO->rollBack();
                return false;
            }

            $this->PDO->commit();
            return true;
        } catch (PDOException $ex) {
            $this->PDO->rollBack();
            Logger::WriteException(LogFile::DB, $ex);
            return false;
        }
    }
}