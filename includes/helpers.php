<?php
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
?>