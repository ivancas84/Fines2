<?php

namespace Fines2;

use SqlOrganize\Sql\Config as SqlConfig;
use SqlOrganize\Model\Config as ModelConfig;

define("MAIN_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Fines2/");
define("DB_HOST_FINES2", "localhost");
define("DB_NAME_FINES2", "planfi10_20204");
define("DB_USER_FINES2", "root");
define("DB_PASS_FINES2", "");

require_once MAIN_PATH . 'SqlOrganize/Sql/Config.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Config.php';

class MainConfig
{
    public static function getConfigDb(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_FINES2;
        $configDb->dbName = DB_NAME_FINES2;
        $configDb->user = DB_USER_FINES2;
        $configDb->pass = DB_PASS_FINES2;
        $configDb->namespace = "Fines2";
        $configDb->dataClassesPath = MAIN_PATH . "Model" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

    public static function getConfigModel(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_FINES2;
        $configModel->dbName = DB_NAME_FINES2;
        $configModel->user = DB_USER_FINES2;
        $configModel->pass = DB_PASS_FINES2;
        $configModel->namespace = "Fines2";
        $configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\";
        $configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\Model\\";
        return $configModel;

    }
}














