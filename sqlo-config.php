<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Config as SqlConfig;
use SqlOrganize\Model\Config as ModelConfig;

define("MAIN_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Fines2/");
define("DB_HOST_FINES", "localhost");
define("DB_NAME_FINES", "planfi10_20204");
define("DB_USER_FINES", "root");
define("DB_PASS_FINES", "");

require_once MAIN_PATH . 'SqlOrganize/Sql/Config.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Config.php';

class MainConfig
{
    public static function getConfigDb(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_FINES;
        $configDb->dbName = DB_NAME_FINES;
        $configDb->user = DB_USER_FINES;
        $configDb->pass = DB_PASS_FINES;
        $configDb->mainPath = MAIN_PATH;
        $configDb->namespace = "Fines2";
        return $configDb;
    }

    public static function getConfigModel(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_FINES;
        $configModel->dbName = DB_NAME_FINES;
        $configModel->user = DB_USER_FINES;
        $configModel->pass = DB_PASS_FINES;
        $configModel->namespace = "Fines2";
        $configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\";
        $configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\Data\\";
        return $configModel;

    }
}














