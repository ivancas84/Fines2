<?php

namespace Fines2;

use \Fines2\Asignatura;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Asignatura_ extends Asignatura
{
    public function getLabel(): string{
        return $this->nombre . " " . $this->codigo ?? "?";
    }
}

