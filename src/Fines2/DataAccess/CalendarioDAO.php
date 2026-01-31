<?php

namespace Fines2\DataAccess;

use SqlOrganize\Sql\DbMy;
class CalendarioDAO
{

    public static function calendarios(): array{
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id
                FROM calendario 
                ORDER BY anio DESC, semestre DESC";
        return $dataProvider->fetchAllEntitiesBySqlId("calendario", $sql);
    }
}