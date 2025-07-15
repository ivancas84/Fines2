<?php

namespace Fines2;

use \Fines2\Plan;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;
use SqlOrganize\Utils\ValueTypesUtils;

class Plan_ extends Plan
{
    public function getLabel(): string{
        return ValueTypesUtils::acronym($this->orientacion ?? "") . " " . ($this->resolucion ?? "?"); 
    }
}

