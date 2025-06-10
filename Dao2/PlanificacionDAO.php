<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
 
class PlanificacionDAO
{

    public static function planificaciones(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT planificacion.id, 
                FROM planificacion 
                INNER JOIN plan ON planificacion.plan = plan.id 
                ORDER BY plan.resolucion, plan.orientacion, planificacion.anio, planificacion.semestre";
        return $dataProvider->fetchEntitiesBySqlId("planificacion", $sql);
    }

}