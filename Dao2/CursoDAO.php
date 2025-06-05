<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
 
class TomaDAO
{


    public static function CursosByCalendario($calendario, $orderBy): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT id, CONCAT(planificacion.anio, ' ', planificacion.semestre) AS tramo,
            FROM curso 
            INNER JOIN comision ON (comision.id = curso.comision)
            INNER JOIN planificacion ON (planificacion.id = comision.planificacion)
            AND comision.calendario = :idCalendario
            {$orderBy}
        ";

        return $dataProvider->fetchEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
    }

}