<?php

namespace Fines2\Model;

use \Fines2\Model\Persona;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\CompareParams;
use SqlOrganize\Utils\ValueTypesUtils;

use Exception;
use DateTime;
use Override;

class Persona_ extends Persona
{
    public function getEmails(): string {
        $emails = [];
        if (!empty(trim($this->email_abc))) array_push($emails, $this->email_abc);
        if (!empty(trim($this->email))) array_push($emails, $this->email);
        return implode(", ", $emails);
    }

    
    public function getLabel(): string {
        return $this->getNombre() . " "
        . ($this->numero_documento  ?? "?");
    }

    public function getNombre(): string {
        return $this->getApellidos() . " " 
        . $this->getNombres();
    }

    public function getApellidos(): string {
        return (ValueTypesUtils::toUpperCase($this->apellidos) ?? "?");
    }

    public function getNombres(): string {
        return (ValueTypesUtils::toTitleCase($this->nombres)  ?? "?");
    }

    public function getCuilDni(){
        return (!empty($this->cuil) && str_contains($this->cuil,$this->numero_documento)) ? $this->cuil : $this->numero_documento;
    }

    public function getSexo(): ?string {
        if ($this->sexo == 1) return "Masculino";
        elseif ($this->sexo == 2) return "Femenino";
        elseif ($this->sexo == 3) return "No Binario";
        return null;
    }

    public static function cuilDni(string $cuilDni): array {
        $cuilDni = ValueTypesUtils::cleanStringOfNonDigits($cuilDni);
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

    public function getFechaNacimiento(){
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : "";
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

    
    public function ssetIfNullFromPf(array $data): void
    {
                // Campos simples
        $map = [
            'nombre'   => 'nombres',
            'apellido' => 'apellidos',
            'dni'      => 'numero_documento',
            'cuil1'    => 'cuil1',
            'cuil2'    => 'cuil2',
            'email'    => 'email',
            'dia_nac'    => 'dia_nacimiento',
            'mes_nac'    => 'mes_nacimiento',
            'ano_nac'    => 'anio_nacimiento',
            'sexo' => 'sexo',
            'nacionalidad' => 'nacionalidad',
            'codigo_area' => 'codigo_area',
            'nro_telefono' => 'telefono',

        ];

        foreach ($map as $input => $prop) {
            if (isset($data[$input])) {
                $this->ssetIfNull($prop, $data[$input]);
            }
        }

    }

    public function ssetNotNullFromPF(array $data): void 
    {
        // Campos simples
        $map = [
            'nombre'   => 'nombres',
            'apellido' => 'apellidos',
            'dni'      => 'numero_documento',
            'cuil1'    => 'cuil1',
            'cuil2'    => 'cuil2',
            'email'    => 'email',
            'dia_nac'    => 'dia_nacimiento',
            'mes_nac'    => 'mes_nacimiento',
            'ano_nac'    => 'anio_nacimiento',
            'sexo' => 'sexo',
            'nacionalidad' => 'nacionalidad',
            'codigo_area' => 'codigo_area',
            'nro_telefono' => 'telefono',

        ];

        foreach ($map as $input => $prop) {
            if (isset($data[$input])) {
                $this->sset($prop, $data[$input]);
            }
        }

    }


     public function toArrayPF(): array{
        /** @var array */ $data = [
        "mi_periodo"   => PF_PERIODO,
        "apellido"     => $this->getApellidos(),
        "nombre"       => $this->getNombres(),
        "cuil1"        => $this->cuil1,
        "dni_cargar"   => $this->numero_documento,
        "cuil2"        => $this->cuil2,
        "nacionalidad" => $this->nacionalidad ?? "Argentina",
        "dia_nac"      => $this->dia_nacimiento,
        "mes_nac"      => $this->mes_nacimiento,
        "ano_nac"      => $this->anio_nacimiento,
        'email'        => $this->email,
        'nacionalidad' => $this->nacionalidad,
        'cod_area'     => $this->codigo_area,
        'nro_telefono' => $this->telefono,
        ];

        if(!empty($this->sexo))
            $data["sexo"] =  $this->sexo;
        else 
            $data["sexo"] = str_contains(strtolower($this->genero), 'a') ? 1 : 2;

        return $data;



    }

}

