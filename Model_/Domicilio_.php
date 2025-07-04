<?php

namespace Fines2;

use \Fines2\Domicilio;


class Domicilio_ extends Domicilio
{
    public function getLabel(): string {
        $dom = $this->calle . " e/" . ($this->entre ?? "?") . " N°" . $this->numero . ")";
        if(!empty($this->barrio)) {
            $dom .= " " . $this->barrio;
        }
        $dom .= " " . $this->localidad;
        return $dom;
    }

}

