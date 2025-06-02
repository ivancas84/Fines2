<?php

namespace SqlOrganize\Sql\Fines2;

require_once MAIN_PATH . 'SqlOrganizeMy\Sql\DbMy.php';
require_once MAIN_PATH . 'SqlOrganize\Sql\ISchema.php';
require_once MAIN_PATH . 'SqlOrganize\Sql\ModifyQueries.php';
require_once MAIN_PATH . 'SqlOrganize\Utils\ValueTypesUtils.php';
require_once MAIN_PATH . 'Model\Schema.php';


use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;
use SqlOrganize\Sql\Fines2\Schema;

class Context
{
    public static DbMy $db;

    public function __construct()
    {
        $schema = new Schema();

        self::$db = new DbMy($schema);

    }

}
