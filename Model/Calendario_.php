<?php

namespace Fines2;

use \Fines2\Calendario;
use SqlOrganize\Sql\DbMy;

class Calendario_ extends Calendario
{

    public function getLabel(): string {
        return $this->anio . "-" . $this->semestre . " " . $this->descripcion;
    }

    public static function calendarios(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id
                FROM calendario 
                ORDER BY anio DESC, semestre DESC";
        return $dataProvider->fetchAllEntitiesBySqlId("\Fines2\Calendario_", $sql);
    }
}
