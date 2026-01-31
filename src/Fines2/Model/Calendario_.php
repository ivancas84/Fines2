<?php

namespace Fines2\Model;

use \Fines2\Model\Calendario;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Calendario_ extends Calendario
{
    public function getLabel(): string {
        return $this->anio . "-" . $this->semestre . " " . $this->descripcion;
    }
}

