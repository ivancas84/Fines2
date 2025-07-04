<?php

namespace Fines2;

use \Fines2\Persona;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\CompareParams;

use Exception;
use DateTime;
use SqlOrganize\Sql\DbMy;
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

    public static function nombreParecido(array $persona1, array $persona2, int $length = 5): bool {
        // Obtener y normalizar los nombres y apellidos a minúsculas
        $nombres1   = isset($persona1["nombres"]) ? mb_strtolower($persona1["nombres"]) : '';
        $apellidos1 = isset($persona1["apellidos"]) ? mb_strtolower($persona1["apellidos"]) : '';
        $nombres2   = isset($persona2["nombres"]) ? mb_strtolower($persona2["nombres"]) : '';
        $apellidos2 = isset($persona2["apellidos"]) ? mb_strtolower($persona2["apellidos"]) : '';
    
        // Convertir a arrays de palabras
        $tokens1 = array_merge(explode(' ', $nombres1), explode(' ', $apellidos1));
        $tokens2 = array_merge(explode(' ', $nombres2), explode(' ', $apellidos2));
    
        // Recorrer todas las combinaciones posibles
        foreach ($tokens1 as $token1) {
            $token1Prefix = mb_substr($token1, 0, $length);
            foreach ($tokens2 as $token2) {
                if (mb_strpos($token2, $token1Prefix) === 0) {
                    return true;
                }
            }
        }
    
        return false;
    }

    public function compare(Entity $entity, ?CompareParams $cp = null): array
    {   
        $e1 = $this->toArray();
        $e2 = $entity->toArray();
        $response = [];
        if(!self::nombreParecido($this->toArray(), $entity->toArray(), 5)){
            $response["nombres"] = $e2["nombres"] ?? "";
            $response["apellidos"] = $e2["apellidos"] ?? "";
        }
        unset($e1["nombres"], $e2["nombres"]);
        unset($e1["apellidos"], $e2["apellidos"]);
        return array_merge($response, $this->_db->compare($this->_entityName, $e1, $e2, $cp));
    }


}

