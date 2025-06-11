<?php

namespace Fines2;

use \Fines2\Disposicion;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;


class Disposicion_ extends Disposicion
{

    public function getLabel(): string {
        return ($disposicion->asignatura_?->nombre ?? "?"). " " . 
            ($disposicion->planificacion_?->anio ?? "?"). "/" .
            ($disposicion->planificacion_?->semestre ?? "?") . " " .
            ($disposicion->planificacion_?->plan_?->resolucion ?? "?") . " " . 
            ValueTypesUtils::acronym($disposicion->planificacion_?->plan_?->orientacion ?? ""); 
    }


    public static function disposicionesActuales(): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT disposicion.id
            FROM disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura 
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion 
            INNER JOIN plan ON plan.id = planificacion.plan 
            WHERE plan.id IN ('202303101', '202303102', '4', '5') 
            ORDER BY asignatura.nombre, planificacion.anio, planificacion.semestre, plan.resolucion, plan.orientacion ASC;
        ";

        return $dataProvider->fetchEntitiesBySqlId("disposicion", $sql);
    }

}

