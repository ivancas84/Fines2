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

    public static function cuilDni(string $cuilDni): array {
        $cuilDni = preg_replace('/\D/', '', $cuilDni);
        $return = [ "cuil" => null, "dni" => null, "cuil1" => null, "cuil2" => null ];
        if (strlen($cuilDni) === 7 || strlen($cuilDni) === 8) {
            $return["dni"] = $cuilDni;
        } elseif (strlen($cuilDni) === 11) {
            $return["cuil"] = $cuilDni;
            $return["cuil1"] = substr($cuilDni, 0, 2);
            $return["dni"] = substr($cuilDni, 2, 8);
            $return["cuil2"] = substr($cuilDni, 10, 1);
        }
        return $return;
    }
}

