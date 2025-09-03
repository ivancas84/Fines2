<?php

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/main-config.php';

require_once MAIN_PATH . 'SqlOrganize/Model/requires.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Model/requires.php';

require_once MAIN_PATH . 'schema-pedidos.php';

use SqlOrganize\Model\BuildClasses;
use Pedidos\SchemaPedidos;


BuildClasses::Build(App\Context::getConfigModel(), SchemaPedidos::getEntities());

echo "Fin";