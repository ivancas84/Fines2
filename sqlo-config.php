<?php

namespace Fines2;

use SqlOrganize\Sql\Config as SqlConfig;
use SqlOrganize\Model\Config as ModelConfig;

define("MAIN_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Fines2/");
define("DB_HOST_FINES2", "localhost");
define("DB_NAME_FINES2", "planfi10_20204");
define("DB_USER_FINES2", "root");
define("DB_PASS_FINES2", "");

define("DB_HOST_PEDIDOS2", "localhost");
define("DB_NAME_PEDIDOS2", "planfi10_wp211");
define("DB_USER_PEDIDOS2", "root");
define("DB_PASS_PEDIDOS2", "");

define("CALENDARIO_ID_ACTUAL", "202508131824");
define("CALENDARIO_ID_ANTERIOR", "202502110007");
define("DOCENTES_PATH", "/home/planfi10/domains/planfines2.com.ar/public_html/upload2/docentes.json");
define("TOMAS_PATH", "/home/planfi10/domains/planfines2.com.ar/public_html/Tomas/"); //crear subdirectorio con el id del calendario
//define("TOMAS_PATH", "C:\\xampp\\htdocs\\Fines2\\Tomas\\"); //crear subdirectorio con el id del calendario
define("IMAGES_PATH", "/home/planfi10/domains/planfines2.com.ar/public_html/images/"); //crear subdirectorio con el id del calendario

define("EMAIL_DOCENTES_HOST", "mail.planfines2.com.ar");
define("EMAIL_DOCENTES_USER", "docentes@planfines2.com.ar");
define("EMAIL_DOCENTES_PASSWORD", "Fines2023");
define("EMAIL_DOCENTES_FROM_NAME", "Docentes CENS 462");
define("EMAIL_DOCENTES_FROM_ADDRESS", "docentes@planfines2.com.ar");
define("EMAIL_DOCENTES_BCC", "docentes.cens462@gmail.com");


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

    public static function getConfigDbPedidos(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_PEDIDOS2;
        $configDb->dbName = DB_NAME_PEDIDOS2;
        $configDb->user = DB_USER_PEDIDOS2;
        $configDb->pass = DB_PASS_PEDIDOS2;
        $configDb->namespace = "Pedidos";
        $configDb->dataClassesPath = MAIN_PATH . "ModelPedidos" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

     public static function getConfigModelPedidos(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_PEDIDOS2;
        $configModel->dbName = DB_NAME_PEDIDOS2;
        $configModel->user = DB_USER_PEDIDOS2;
        $configModel->pass = DB_PASS_PEDIDOS2;
        $configModel->namespace = "Pedidos";
        $configModel->schemaName = 'schema_pedidos';
        $configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\";
        $configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\ModelPedidos\\";
        return $configModel;

    }
}














