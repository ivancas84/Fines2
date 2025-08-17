<?php

namespace Fines2;

use \Fines2\Calendario;
use SqlOrganize\Sql\DbMy;

class Calendario_ extends Calendario
{

    public function getLabel(): string {
        return $this->anio . "-" . $this->semestre . " " . $this->descripcion;
    }

    
}
