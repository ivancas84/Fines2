<?php

namespace Fines2;

use \Fines2\Planificacion;
use SqlOrganize\Sql\DbMy;

class Planificacion_ extends Planificacion
{
    public function getTramo(): string {
        return ($this->anio ?? "?") . "°" . ($this->semestre ?? "?") . "C";
    }

    public function getLabel(): string {
        return ($this->plan_?->orientacion ?? "?") . " " 
        . ($this->plan_?->resolucion  ?? "?") . " "
        . ($this->anio  ?? "?") . "/"
        . ($this->semestre  ?? "?");
    }

    public static function planificaciones(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT planificacion.id
                FROM planificacion 
                INNER JOIN plan ON planificacion.plan = plan.id 
                ORDER BY plan.resolucion, plan.orientacion, planificacion.anio, planificacion.semestre";

        $entities = $dataProvider->fetchEntitiesBySqlId("planificacion", $sql);
        return $entities;
    }




}

