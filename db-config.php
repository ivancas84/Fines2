<?php


require_once __DIR__ . '/sqlo-config.php';

require_once MAIN_PATH . 'SqlOrganize/Sql/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Sql/requires.php';
require_once MAIN_PATH . 'schema.php';

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\Fines2\Schema;
use SqlOrganize\Sql\Fines2\MainConfig;


DbMy::initInstance(MainConfig::getConfigDb(), Schema::getEntities());

