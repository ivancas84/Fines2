<?php

namespace Fines2;

use \Fines2\Planificacion;

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

}

