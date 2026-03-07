<?php

namespace Fines2\Model;

use \Fines2\Model\Planificacion;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Utils\ValueTypesUtils;
use Exception;
use DateTime;

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

    public function getTramoSiguiente(): ?array {
        $a = intval($this->anio);
        $s = intval($this->semestre);
        if($s == 2) {
            if($a == 3) return null;
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

    public function getAnioLetras(){
        return mb_strtoupper(ValueTypesUtils::toOrdinalSpanish(intval($this->anio)));
    }
}

