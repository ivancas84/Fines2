<?php

namespace Fines2;

use \Fines2\Alumno;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\DbMy;
use Exception;
use DateTime;

class Alumno_ extends Alumno
{
    public static function estados_inscripcion(): array {
        $sql = "SELECT DISTINCT estado_inscripcion FROM alumno ORDER BY estado_inscripcion";
        
        return DbMy::getInstance()->CreateDataProvider()->fetchColumnBySql($sql, 0);
    }
}

