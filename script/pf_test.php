<?php

use ProgramaFines\DataAccess\PfDAO;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';



$pf = new PfDAO("0e9cbbb7ba8005bbee2af0d40c3cb4a0");

/*
$html = $pf->getSubCategorias(6);

echo $html;

*/


$page = $pf->getPage(
    "https://www.programafines.ar/inicial/index4.php",
    [
        "a" => 12,
        "nom_comision" => "00396",
        "mi_periodo" => 6
    ]
);

echo $page;
