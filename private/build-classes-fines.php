<?php

set_time_limit(300); // 300 seconds = 5 minutes

require __DIR__ . '/../vendor/autoload.php';

use App\Context;
use SqlOrganize\Model\BuildClasses;
use Fines2\Schema_;

BuildClasses::Build(Context::getConfigModel(), Schema_::getEntities());

echo "fin";