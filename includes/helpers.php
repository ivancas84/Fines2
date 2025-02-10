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

function tramoSiguiente($tramo){
	
		switch($tramo){
		}
}
?>