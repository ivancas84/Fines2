<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/sqlo-config.php';
require_once MAIN_PATH . 'SqlOrganize/Model/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Model/requires.php';

use SqlOrganize\Model\BuildSchemaMy;
use Fines2\MainConfig;


$schema = new BuildSchemaMy(MainConfig::getConfigModel());
$schema->createSchema();

echo "Fin";