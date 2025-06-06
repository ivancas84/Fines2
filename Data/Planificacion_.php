<?php

namespace Fines2;

use \Fines2\Planificacion;

class Planificacion_ extends Planificacion
{
    public function getTramo(): string {
        return ($this->anio ?? "?") . "°" . ($this->semestre ?? "?") . "C";
    }

}

