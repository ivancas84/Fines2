<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/config.php';

require_once MAIN_PATH . 'SqlOrganize/Sql/BuildClasses.php';
require_once MAIN_PATH . 'Model/Schema.php';

use SqlOrganize\Sql\Fines2\Schema;

$schema = new Schema();

SqlOrganize\Sql\BuildClasses::Build($configModel, $schema);
