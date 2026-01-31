<?php

namespace Fines2\Model;

use \Fines2\Model\Sede;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Sede_ extends Sede
{
    public ?string $designaciones = null;
    
    public function getLabel(): string {
        return $this->nombre . " (" . $this->numero . ")";
    }
}

