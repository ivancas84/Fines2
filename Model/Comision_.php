<?php

namespace Fines2;

use \Fines2\Comision;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Comision_ extends Comision
{
    public function getLabel(): string
    {
        return ($this->pfid ?? "S/N") . " " . 
        ($this->sede_->getLabel() ?? "?") . " " .  
        ($this->planificacion_?->getLabel() ?? "?");
        ($this->calendario_?->getLabel() ?? "?");
    }
}

