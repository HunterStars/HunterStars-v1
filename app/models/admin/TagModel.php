<?php

namespace HS\app\models\admin;

use HS\config\DBAccount;
use HS\libs\database\DB;
use HS\libs\database\DBTools;
use PDO;

class TagModel extends DB
{
    public function __construct(DBAccount|PDO|DB $account = null)
    {
        parent::__construct($account ?? DBAccount::catalog);
    }

    public function Add(string $user_id, string $circle, string $name): bool
    {
        return $this->SelectOnly('SELECT tag_Add(?, ?, ?)', [
                $user_id, $circle, $name
            ]) === 1;
    }

    public function GetID(string $user_id, string $circle, string $name): int|array
    {
        return $this->SelectOnly('CALL tag_GetId(?, ?, ?)', [
            $user_id, $circle, $name
        ]);
    }

    public function Search(string $user_id, string $circle, string $name): array
    {
        return $this->SelectAll('CALL tag_Search(?, ?, ?)', [
            $user_id, $circle, $name
        ]);
    }

    public function InsertAll(string $user_id, string $circle_id, string $projectId, array $tagsId): bool
    {
        return $this->Transaction(function () use ($circle_id, $user_id, $projectId, $tagsId) {
            //Eliminando todas las categorías del proyecto.
            $deleted = $this->SelectOnly('CALL project_DeleteAllCategories(?, ?, ?)', [$user_id, $circle_id, $projectId]);

            //Si no se tiene permiso para modificar el proyecto, cancelar operación.
            if (is_array($deleted) && empty($deleted))
                return false;

            if (count($tagsId) > 0) {
                //Preparando consulta.
                $sentence = $this->GetPDO()->prepare('INSERT INTO project_tags VALUES (:pid, :tid)');

                //Recorriendo arreglo de parámetros.
                foreach ($tagsId as $tag) {
                    //Vinculando parámetros de la consulta.
                    DBTools::BindValue($sentence, ['pid' => $projectId, 'tid' => $tag]);

                    //Ejecutando consulta.
                    $sentence->execute();
                }
            }

            return true;
        });
    }

}