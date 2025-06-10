<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
 
class CalendarioDAO
{

    public static function calendarios(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id
                FROM calendario 
                ORDER BY anio DESC, semestre DESC";
        return $dataProvider->fetchEntitiesBySqlId("calendario", $sql);
    }

}