<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use SqlOrganizeMy\Model\BuildSchemaMy;

$schema = new BuildSchemaMy(App\Context::getConfigModelFines());
//$schema->createSchema();
$schema->createSchemaJson();
echo "Fin";