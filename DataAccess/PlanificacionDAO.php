<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use Fines2\Planificacion_;

class PlanificacionDAO
{
 
   public static function planificaciones(): array{
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT planificacion.id
                FROM planificacion 
                INNER JOIN plan ON planificacion.plan = plan.id 
                ORDER BY plan.resolucion, plan.orientacion, planificacion.anio, planificacion.semestre";

        $entities = $dataProvider->fetchAllEntitiesBySqlId("planificacion", $sql);
        return $entities;
    }
}