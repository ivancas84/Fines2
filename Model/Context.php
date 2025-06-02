<?php

namespace SqlOrganize\Sql\Fines;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\Config;
use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Sql\ValueTypesUtils;
use SqlOrganize\Sql\Schema;


class Context
{
    public static ?DbSs $db = null;

    private function __construct()
    {
        $settings = parse_ini_file(__DIR__ . '/config.ini', true);

        if (empty($settings["connectionString"]) ||
            empty($settings["username"]) ||
            empty($settings["password"])
            ) {
            throw new \Exception("Propiedades no definidas, revisar la configuración");
        }

        $schema = new Schema();

        self::$db = new DbMy(self::$config, $schema);

    }

}
