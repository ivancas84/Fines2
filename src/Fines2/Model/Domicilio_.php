<?php

namespace Fines2\Model;

use \Fines2\Model\Domicilio;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

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

