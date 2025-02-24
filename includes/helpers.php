<?php

function toTitleCase($string) {
    return ucwords(strtolower($string));
}

function toOrdinalSpanish($number) {
    $ordinals = [1 => "PRIMERO", 2 => "SEGUNDO", 3 => "TERCERO", 4 => "CUARTO", 5 => "QUINTO"];
    return $ordinals[$number] ?? $number . "°";
}

function isNoE($string) {
    return empty(trim($string));
}

function getAcronym($string) {
    $ignoreWords = ['de', 'y', 'la', 'el', 'los', 'las', 'en', 'del', 'al']; // Words to ignore
    $words = explode(' ', $string);
    $acronym = '';

    foreach ($words as $word) {
        $word = strtolower(trim($word)); // Normalize case and trim spaces
        if (!in_array($word, $ignoreWords) && !empty($word)) {
            $acronym .= strtoupper($word[0]); // Get first letter
        }
    }

    return $acronym;
}

function boolToSiNo($value) {
    return $value ? 'Si' : 'No';
}

function tramoSiguiente($tramo): string{
    switch($tramo){
        case "11":
            return "12";
            break;
        case "12":
            return "21";
            break;
        case "21":
            return "22";
            break;
        case "22":
            return "31";
            break;
        case "31":
            return "32";
            break;

    }
}

function mes($numero_mes){
    $meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    return $meses[$numero_mes - 1];
}


function fechaActualDiaDeMesDeAnio(){
    $fecha = new DateTime();
    $dia = $fecha->format('d');
    $mes = mes($fecha->format('n')); // Obtener el mes en español
    $anio = $fecha->format('Y');

    return "$dia de $mes de $anio";
}

?>