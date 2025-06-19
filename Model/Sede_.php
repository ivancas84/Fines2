<?php

namespace Fines2;

use \Fines2\Sede;

use SqlOrganize\Sql\DbMy;

class Sede_ extends Sede
{
    public static function Sedes462(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id 
                FROM sede WHERE centro_educativo = '6047d36d50316'  
                ORDER BY nombre ASC";
        return $dataProvider->fetchEntitiesBySqlId("sede", $sql);
    }
}

