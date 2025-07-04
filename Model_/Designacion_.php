<?php

namespace Fines2;

use \Fines2\Designacion;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Designacion_ extends Designacion
{

    public function getLabel(): string
    {
        return ($this->persona_?->nombres ?? "?"). " " . ($this->persona_?->apellidos ?? "?") . " " . ($this->persona_?->telefono ?? "?");
    }
    

}

