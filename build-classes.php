<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/sqlo-config.php';

require_once MAIN_PATH . 'SqlOrganize/Model/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Model/requires.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Sql/requires.php';
require_once MAIN_PATH . 'schema.php';
require_once MAIN_PATH . 'schema_.php';

use SqlOrganize\Model\BuildClasses;
use Fines2\Schema_;
use Fines2\MainConfig;

BuildClasses::Build(MainConfig::getConfigModel(), Schema_::getEntities());

echo "Fin";