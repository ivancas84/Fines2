<?php

use ProgramaFines\DataAccess\PfDAO;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$pf = new PfDAO($sessionId);

foreach ($alumnos as $a) {

    $pf->agregarAlumnoPCI([
        "apellido" => $a->apellido,
        "nombre" => $a->nombre,
        "dni" => $a->dni,
        "cuil1" => 20,
        "cuil2" => 9,
        "sexo" => 1,
        "dia" => 1,
        "mes" => 1,
        "ano" => 2000,
        "periodo" => 6,
        "comision" => 20001,
        "cuatrimestre" => 2
    ]);

}