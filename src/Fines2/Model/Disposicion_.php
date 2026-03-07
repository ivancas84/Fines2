<?php

namespace Fines2\Model;

use \Fines2\Model\Disposicion;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Utils\ValueTypesUtils;

use Exception;
use DateTime;

class Disposicion_ extends Disposicion
{
    public function getLabel(): string {
        return ($this->asignatura_?->nombre ?? "?") . " " . 
            ($this->asignatura_?->codigo ?? "?") . " " . 
            ($this->planificacion_?->anio ?? "?"). "/" .
            ($this->planificacion_?->semestre ?? "?") . " " .
            ($this->planificacion_?->plan_?->resolucion ?? "?") . " " . 
            ValueTypesUtils::acronym($this->planificacion_?->plan_?->orientacion ?? "") . " (" .
            ($this->horas_catedra ?? "?") . ")"; 
    }
}

