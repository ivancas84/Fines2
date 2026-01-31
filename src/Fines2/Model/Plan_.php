<?php

namespace Fines2\Model;

use \Fines2\Model\Plan;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Utils\ValueTypesUtils;

use Exception;
use DateTime;

class Plan_ extends Plan
{
public function getLabel(): string{
        return ValueTypesUtils::acronym($this->orientacion ?? "") . " " . ($this->resolucion ?? "?"); 
    }
}

