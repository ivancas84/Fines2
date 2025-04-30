<?php
class Tools {
    public static function excelParse(string $rawData): array {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawData));

        $rows = array_map(function($line) {
            return array_map('trim', explode("\t", $line));
        }, $lines);

        $headers = array_shift($rows);

        return array_map(function($row) use ($headers) {
            return array_combine($headers, $row);
        }, $rows);
    }

    public static function formatCalificacionValue($value){
        $value = intval(trim($value));
        if($value < 7){
            throw new Exception("Calificación vacía o menor a 7");
        }

        return $value;
    }

    public static function parseFirstColumnCalificacionPF($inputString) {
        // Define the regex pattern to match the structure
        $pattern = '/^(\d+)\s+([^,]+),\s+(.+?)\s+DNI\s+(\d+)$/';
        
        // Apply the regex pattern
        if (preg_match($pattern, $inputString, $matches)) {
            // $matches[1] contains the position number
            // $matches[2] contains the apellidos (surnames)
            // $matches[3] contains the nombres (first names)
            // $matches[4] contains the documento (document number)
            
            $apellidos = trim($matches[2]);
            $nombres = trim($matches[3]);
            $numero_documento = trim($matches[4]);
            
            $response = [
                'apellidos' => $apellidos,
                'nombres' => $nombres,
                'numero_documento' => $numero_documento
            ];

        } else {
            return false; // Pattern not matched
        }
    }
    
    
}