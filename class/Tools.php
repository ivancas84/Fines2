<?php
class Tools {

    
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
                $result[$item[$key]][] = $item;
            }
        }
        return $result;
    }
}