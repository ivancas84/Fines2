<?php
class Tools {


    public static function newArray($array, $fieldNames){
        $newArray = [];
        foreach ($fieldNames as $fieldName) {
            if (isset($array[$fieldName])) {
                $newArray[$fieldName] = $array[$fieldName];
            } else {
                $newArray[$fieldName] = null; // O cualquier valor por defecto que desees
            }
        }
        return $newArray;
    }

    public static function toBool($value): bool {
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'yes', 'y', 'si', 's', 'ok', 't', 'on'], true);
        }
        return (bool)$value;
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
    
    public static function excelParseIgnorePrefix(string $rawData, string $ignore = "_"): array {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawData));
    
        if (empty($lines)) {
            return [];
        }
    
        $rows = array_map(fn($line) => array_map('trim', explode("\t", $line)), $lines);
    
        $headers = array_shift($rows);
    
        // Filter headers and store the indices to keep
        $validIndexes = [];
        $validHeaders = [];
        foreach ($headers as $index => $header) {
            if (strpos($header, $ignore) !== 0) {
                $validIndexes[] = $index;
                $validHeaders[] = $header;
            }
        }
    
        $result = [];
        foreach ($rows as $row) {
            $assocRow = [];
            foreach ($validIndexes as $i => $index) {
                $assocRow[$validHeaders[$i]] = $row[$index] ?? null;
            }
            $result[] = $assocRow;
        }
    
        return $result;
    }
    

    public static function excelParse(string $rawData): array {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawData));
    
        $rows = array_map(function($line) {
            return array_map('trim', explode("\t", $line));
        }, $lines);
    
        $headers = array_shift($rows);
        $result = [];
    
        foreach ($rows as $row) {
            if (count($headers) === count($row)) {
                $result[] = array_combine($headers, $row);
            }
            // If headers and row count mismatch, row is ignored
        }
    
        return $result;
    }
    
    public static function organizeArrayByKey(array $array, string $key): array {
        $result = array();
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $result[$item[$key]] = $item;
            }
        }
        return $result;
    }
}