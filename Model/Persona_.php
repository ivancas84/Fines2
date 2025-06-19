<?php

namespace Fines2;

use \Fines2\Persona;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;
use SqlOrganize\Utils\ValueTypesUtils;

class Persona_ extends Persona
{
    public function getLabel(): string {
        return (mb_strtoupper($this->apellidos) ?? "?") . " " 
        . (ValueTypesUtils::toTitleCase($this->nombres)  ?? "?") . " "
        . ($this->numero_documento  ?? "?");
    }
}

