<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/../SqlOrganizeMy/Model/BuildSchemaMy.php';

use SqlOrganize\Model\BuildSchemaMy;

$schema = new BuildSchemaMy();
$schema->createSchema();
