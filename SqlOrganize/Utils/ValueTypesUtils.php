<?php

namespace SqlOrganize\Utils;
use DateTimeInterface;
class ValueTypesUtils
{

/**
 * Crea un diccionario de objetos indexado por la concatenación de propiedades.
 *
 * @template T
 * @param iterable $objects Array de objetos
 * @param string ...$propertyNames Nombres de las propiedades a concatenar como clave
 * @return array<string, mixed> Diccionario clave → objeto
 */
public static function dictOfObjByPropertyNames(iterable $objects, string ...$propertyNames): array {
    $result = [];

    foreach ($objects as $obj) {
        $values = [];

        foreach ($propertyNames as $propName) {
            // Accede a la propiedad (si es pública o mediante __get)
            if (is_object($obj)) {
                $value = null;

                // Soporta objetos con propiedades públicas o __get
                if (property_exists($obj, $propName)) {
                    $value = $obj->$propName;
                } elseif (method_exists($obj, '__get')) {
                    $value = $obj->$propName;
                }

                $values[] = strval($value);
            }
        }

        $key = implode('~', $values);
        $result[$key] = $obj;
    }

    return $result;
}


public static function toStringDict(array $param): string {
    $dictionaryString = '{';
    foreach ($param as $key => $value) {
        $dictionaryString .= $key . ' : ' . ($value !== null ? $value : 'null') . ', ';
    }
    return rtrim($dictionaryString, ', ') . '}';
}


public static function arrayOfName(iterable $objects, string $name): array
{
    $result = [];

    foreach ($objects as $object) {
        if (is_object($object) && property_exists($object, $name)) {
            $result[] = $object->$name;
        } elseif (is_array($object) && array_key_exists($name, $object)) {
            $result[] = $object[$name];
        }
    }

    return $result;
}

    public static function generateGuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }



    /**
     * Convierte un string a Title Case usando configuración es-AR
     */
    public static function toTitleCase(string $str): string
    {
        $str = str_replace('_', ' ', $str);
        return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Convierte un string a camelCase
     */
    public static function toCamelCase(string $str): string
    {
        $titleCase = self::toTitleCase($str);
        $clean = str_replace([' ', '_'], '', $titleCase);
        return ucfirst($clean);
    }

    /**
     * Genera un string aleatorio de la longitud especificada
     */
    public static function randomString(int $length): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }

    /**
     * Obtiene el siguiente caracter ASCII
     */
    public static function getNextChar(string $c): string
    {
        return chr(ord($c) + 1);
    }

    /**
     * Remueve la última ocurrencia de un caracter
     */
    public static function removeLastChar(string $s, string $c): string
    {
        $index = strrpos($s, $c);
        if ($index !== false) {
            return substr_replace($s, '', $index, 1);
        }
        return $s;
    }

    /**
     * Remueve la última ocurrencia de un string
     */
    public static function removeLastString(string $input, string $toRemove): string
    {
        $lastIndex = strrpos($input, $toRemove);
        if ($lastIndex !== false) {
            return substr_replace($input, '', $lastIndex, strlen($toRemove));
        }
        return $input;
    }

    /**
     * Remueve todos los dígitos de un string
     */
    public static function removeDigits(string $key): string
    {
        return preg_replace('/\d/', '', $key);
    }

    /**
     * Reemplaza la primera ocurrencia de un string
     */
    public static function replaceFirst(string $string, string $oldValue, string $newValue): string
    {
        $pos = strpos($string, $oldValue);
        if ($pos !== false) {
            return substr_replace($string, $newValue, $pos, strlen($oldValue));
        }
        return $string;
    }

    /**
     * Convierte diversos tipos a boolean
     */
    public static function toBool($value): bool
    {
        if (is_string($value)) {
            if (empty($value)) return false;
            $s = strtolower(substr($value, 0, 1));
            return in_array($s, ['t', '1', 's', 'y', 'o']);
        }
        
        if (is_numeric($value)) {
            $ret =  (bool)($value != 0);
            return $ret;
        }
        
        if (is_bool($value)) {
            return $value;
        }
        
        return false;
    }

    /**
     * Convierte string a char (primer caracter)
     */
    public static function toChar(string $string): string
    {
        return substr($string, 0, 1);
    }

    /**
     * Remueve espacios múltiples
     */
    public static function removeMultipleSpaces(string $string): string
    {
        return preg_replace('/\s+/', ' ', $string);
    }

    /**
     * Extrae substring entre dos strings
     */
    public static function substringBetween(string $value, string $a, string $b): string
    {
        $posA = strpos($value, $a);
        if ($posA === false) {
            return '';
        }
        
        $valueAfterA = substr($value, $posA + strlen($a));
        $posB = strpos($valueAfterA, $b);
        if ($posB === false) {
            return '';
        }
        
        return substr($valueAfterA, 0, $posB);
    }

    /**
     * Genera acrónimo de un string
     */
    public static function acronym(string $string): string
    {
        $words = preg_split('/\s+/', trim($string), -1, PREG_SPLIT_NO_EMPTY);
        $acronym = '';
        foreach ($words as $word) {
            $acronym .= substr($word, 0, 1);
        }
        return $acronym;
    }


    /**
     * Quita los caracteres no numéricos de un string
     */
    public static function cleanStringOfDigits(?string $s): ?string
    {
        if (empty($s)) return null;
        return preg_replace('/\d/', '', $s);
    }

    /**
     * Quita los caracteres que no sean dígitos
     */
    public static function cleanStringOfNonDigits(?string $s): string
    {
        if (empty($s)) return '';
        return preg_replace('/[^\d]/', '', $s);
    }

    /**
     * Divide string en parte no numérica y numérica
     */
    public static function divideStringNonDigitsDigits(?string $s): ?array
    {
        if (empty($s)) return null;
        
        $nondig = '';
        $dig = '';
        
        for ($i = 0; $i < strlen($s); $i++) {
            $char = $s[$i];
            if (ctype_digit($char)) {
                $dig .= $char;
            } else {
                $nondig .= $char;
            }
        }
        
        return [$nondig, $dig];
    }

    /**
     * Verifica si el string tiene caracteres que no sean números
     */
    public static function hasNonDigits(?string $s): bool
    {
        if (empty($s)) return false;
        return !ctype_digit($s);
    }

    /**
     * Verifica si dos strings son similares comparando subcadenas
     */
    public static function similarTo(string $name1, string $name2, int $len = 4): bool
    {
        $n1 = explode(' ', strtoupper(self::removeMultipleSpaces(trim($name1))));
        $n2 = explode(' ', strtoupper(self::removeMultipleSpaces(trim($name2))));
        
        foreach ($n1 as $nn1) {
            foreach ($n2 as $nn2) {
                $currentLen = min($len, strlen($nn1));
                $n = substr($nn1, 0, $currentLen);
                if (strpos($nn2, $n) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Convierte número a ordinal en español
     */
    public static function toOrdinalSpanish(string $numberString): string
    {
        $number = intval($numberString);
        
        if ($number <= 0) {
            return $numberString;
        }
        
        $ordinals = [
            1 => 'primero',
            2 => 'segundo',
            3 => 'tercero',
            4 => 'cuarto',
            5 => 'quinto',
            6 => 'sexto',
            7 => 'séptimo',
            8 => 'octavo',
            9 => 'noveno',
            10 => 'décimo'
        ];
        
        return $ordinals[$number] ?? $numberString;
    }

    /**
     * Convierte texto a HTML
     */
    public static function convertTextToHtml(string $text): string
    {
        $html = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return str_replace(["\r\n", "\n"], '<br>', $html);
    }

    /**
     * Genera hash SHA256
     */
    public static function generateHash(string $input): string
    {
        return hash('sha256', $input);
    }


    public static function toString($var): string {
    if ($var instanceof DateTimeInterface) {
        return $var->format('Y-m-d H:i:s');
    } elseif (is_string($var)) {
        return $var;
    } elseif (is_scalar($var) || is_null($var)) {
        return strval($var);
    } elseif (is_object($var)) {
        if (method_exists($var, '__toString')) {
            return $var->__toString();
        } else {
            return json_encode($var);
        }
    } elseif (is_array($var)) {
        return json_encode($var);
    } else {
        return '';
    }
}

    /**
     * Convierte array asociativo a string de pares clave-valor
     */
    public static function toStringKeyValuePair(array $dict): string
    {
        $pairs = [];
        foreach ($dict as $key => $value) {
            $valueStr = $value === null ? 'NULL' : (string)$value;
            $pairs[] = "$key:$valueStr";
        }
        return implode(', ', $pairs);
    }

    public static function filterArrayBySuffix(array $input, string $suffix): array {
        $result = [];
        $suffixLength = strlen($suffix);

        foreach ($input as $key => $value) {
            if (substr($key, -$suffixLength) === $suffix) {
                $newKey = substr($key, 0, -$suffixLength);
                $result[$newKey] = $value;
            }
        }

        return $result;
    }




public static function getPropertyValue($obj, string $propName) {
    if (is_array($obj) && array_key_exists($propName, $obj)) {
        return $obj[$propName];
    }

    if (is_object($obj)) {
        // Check if public property
        if (isset($obj->$propName)) {
            return $obj->$propName;
        }

        // Check if getter method
        $method = 'get' . ucfirst($propName);
        if (method_exists($obj, $method)) {
            return $obj->$method();
        }
    }

    throw new \InvalidArgumentException("Property '$propName' not found in object or array.");
}

/**
 * Agrupa elementos por el valor de una propiedad.
 *
 * @template T
 * @template V
 * @param iterable<V> $source
 * @param string $propName
 * @return array<T, list<V>>
 */
public static function dictOfListByPropertyName(iterable $source, string $propName): array {
    $response = [];

    foreach ($source as $obj) {
        $val = self::getPropertyValue($obj, $propName);

        if (!array_key_exists($val, $response)) {
            $response[$val] = [];
        }

        $response[$val][] = $obj;
    }

    return $response;
}


/**
     * Convierte un array asociativo a un diccionario indexado por una clave específica.
     *
     * @param array $array Array asociativo a convertir.
     * @param string $key Clave por la cual indexar el diccionario.
     * @return array Diccionario indexado por la clave especificada.
     */
 public static function arrayNumToDictByKey(array $array, string $key): array {
        $result = array();
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $result[$item[$key]] = $item;
            }
        }
        return $result;
    }

    /**
     * Parsea datos de Excel ignorando prefijos en los encabezados.
     *
     * @param string $rawData Datos en formato de texto tabulado.
     * @param string $ignore Prefijo a ignorar en los encabezados.
     * @return array Datos parseados como array asociativo.
     */
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


}

if (!function_exists('str_substring_between')) {
    function str_substring_between(string $value, string $a, string $b): string {
        return ValueTypesUtils::substringBetween($value, $a, $b);
    }
}
?>