<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\DesignacionDAO;
use Fines2\Persona_;
use SqlOrganize\Sql\ModifyQueries;
use ProgramaFines\PfDAO;


//$pf = new PfDAO("f17ac5fc2a0fcc5024e096552f00b77c");
//echo $pf->request("https://programafines.ar/inicial/index4.php?a=46");
\App\Context::getFinesDb();;