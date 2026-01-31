<?php

namespace Fines2\DataAccess;

use SqlOrganize\Sql\DbMy;

class SedeDAO
{
    public static function Sedes462(): array{
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id 
                FROM sede WHERE centro_educativo = '6047d36d50316'  
                ORDER BY nombre ASC";
        return $dataProvider->fetchAllEntitiesBySqlId("sede", $sql);
    }
}
