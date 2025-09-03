<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/main-config.php';
require_once MAIN_PATH . 'SqlOrganize/Model/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Model/requires.php';
require_once MAIN_PATH . 'Context.php';

use SqlOrganize\Model\BuildSchemaMy;

$schema = new BuildSchemaMy(App\Context::getConfigModelPedidos());
$schema->createSchema();

echo "Fin";