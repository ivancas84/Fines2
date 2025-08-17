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

    public function getTramoSiguiente(): array {
        $a = intval($this->anio);
        $s = intval($this->semestre);
        if($s == 2) {
            $a++;
            $s = 1;
        } else {
            $s = 2;
        }
        return [
            "anio" => strval($a),
            "semestre" => $s
        ];
    }
    




}

