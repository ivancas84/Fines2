<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';


use \Fines2\TomaDAO;
use \Fines2\CursoDAO;
use \SqlOrganize\Utils\ValueTypesUtils;

use \SqlOrganize\Sql\DbMy;

$calendario_id = CALENDARIO_ID;

$tomas = TomaDAO::TomasContralorByCalendario($calendario_id);
echo "<pre>";
foreach($tomas as $toma){
    print_r($toma->docente_->toArray());
}
