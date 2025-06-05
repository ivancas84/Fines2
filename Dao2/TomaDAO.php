<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
 
class TomaDAO
{


    public static function TomasByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            WHERE calendario = :calendario
        ";

        return $dataProvider->fetchEntitiesByNamedSql("toma", $sql, ["calendario" => $calendario]);
    }

}