<?php

namespace Fines2;

use \Fines2\Modalidad;

use SqlOrganize\Sql\DbMy;

class Modalidad_ extends Modalidad
{
    public static function modalidades(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT * FROM modalidad ORDER BY nombre ASC";
        return $dataProvider->fetchAllEntitiesBySqlId("\Fines2\Modalidad_", $sql);
    }

}

