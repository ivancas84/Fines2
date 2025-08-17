<?php

namespace Fines2;

use \Fines2\Sede;

use SqlOrganize\Sql\DbMy;

class Sede_ extends Sede
{
    

    public ?string $designaciones = null;
    
    public function getLabel(): string {
        return $this->nombre . " (" . $this->numero . ")";
    }




}

