<?php

namespace SqlOrganize\Sql\Fines2;

set_time_limit(300); // 300 seconds = 5 minutes

require_once __DIR__ . '/config.php';

require_once MAIN_PATH . 'Model\Context.php';

$context = new Context();

echo "<pre>";
print_r($context->$db->EntityNames());

v