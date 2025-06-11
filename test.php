<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\Comision_;

$comision_ = ["id"=>1,"division"=>"A"];
$comision = Entity::createFromArray("\Fines2\Comision_", $comision_);
    
print_r($comision->toArray());
