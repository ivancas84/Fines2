<?php

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\ModifyQueries;

require __DIR__ . '/src/bootstrap.php';


$db  = \App\Context::getFinesDb();
$asignatura = $db->GetEntity("asignatura");
print_r($asignatura->toArray());