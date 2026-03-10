<?php

namespace ProgramaFines\Utils;

use DateTime;
use Exception;
use SqlOrganize\Utils\ValueTypesUtils;

class PfUtils
{
    public static function excelDocentesParse($rawData){

        $dnisProcesados = [];
       
        $result = ValueTypesUtils::excelParseIgnorePrefix($rawData);
        $res = [];
        foreach($result as $row) {
            $data = [];
            foreach($row as $key => $value) {
                $k = strtolower($key);
                if($k == "nombre"){
                    $data["nombres"] = $value;
                } else if(str_contains($k, "apellido")){
                    $data["apellidos"] = $value;
                } else if(str_contains($k, "dni")){
                    $data["numero_documento"] = $value;
                    $data["existente"] = false;
                    if(!empty($value) && in_array($data["numero_documento"], $dnisProcesados)){
                        $data["existente"] = true;
                    } else {
                        $dnisProcesados[] = $data["numero_documento"];
                    }
                } else if(str_contains($k, "direccion")){
                    $data["descripcion_domicilio"] = $value;
                } else if(str_contains($k, "localidad.")){
                    $data["localidad"] = $value;
                } else if(str_contains($k, "fechanac")){
                    $data["fecha_nacimiento"] = $value;
                } else if(str_contains($k, "celular")){
                    $data["telefono"] = $value;
                } else if(str_contains($k, "email")){
                    if(str_contains(strtolower($value), "@abc")) {
                        $data["email_abc"] = $value;
                    } else {
                        if(str_contains(strtolower($value), "@"))
                            $data["email"] = $value;
                    } 
                } else if(str_contains($k, "comision")){
                    $data["comision"] = $value;
                } else if(str_contains($k, "materia")){
                    $data["asignatura"] = $value;
                } else if(str_contains($k, "cens")){
                    $data["cens"] = $value;
                }
            }

            $res[] = $data;
        }

        return $res;

    }


 

    
    

}
