<?php

namespace Fines2;

use \Fines2\Disposicion;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;


class Disposicion_ extends Disposicion
{

    public function getLabel(): string {
        return ($this->asignatura_?->nombre ?? "?"). " " . 
            ($this->planificacion_?->anio ?? "?"). "/" .
            ($this->planificacion_?->semestre ?? "?") . " " .
            ($this->planificacion_?->plan_?->resolucion ?? "?") . " " . 
            ValueTypesUtils::acronym($this->planificacion_?->plan_?->orientacion ?? ""); 
    }


    

}

