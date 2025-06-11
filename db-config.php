<?php


require_once __DIR__ . '/sqlo-config.php';

require_once MAIN_PATH . 'SqlOrganize/Sql/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Sql/requires.php';
require_once MAIN_PATH . 'schema.php';
require_once MAIN_PATH . 'schema_.php';


use SqlOrganize\Sql\DbMy;
use Fines2\Schema_;
use Fines2\MainConfig;


DbMy::initInstance(MainConfig::getConfigDb(), Schema_::getEntities());

