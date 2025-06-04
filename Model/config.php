<?php

define("MAIN_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Fines2/");
define("DB_HOST_FINES", "localhost");
define("DB_NAME_FINES", "planfi10_20204");
define("DB_USER_FINES", "root");
define("DB_PASS_FINES", "");

require_once MAIN_PATH . 'SqlOrganize/Sql/Config.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Config.php';

use SqlOrganize\Sql\Config;
use SqlOrganize\Sql\DbMy;

$configDb = new SqlOrganize\Sql\Config();
$configDb->host = DB_HOST_FINES;
$configDb->dbName = DB_NAME_FINES;
$configDb->user = DB_USER_FINES;
$configDb->pass = DB_PASS_FINES;

$configModel = new SqlOrganize\Model\Config();
$configModel->host = DB_HOST_FINES;
$configModel->dbName = DB_NAME_FINES;
$configModel->user = DB_USER_FINES;
$configModel->pass = DB_PASS_FINES;
$configModel->namespace = "Fines2";
$configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\Model";
$configModel->schemaBuilderClassPath = "C:\\xampp\\htdocs\\Fines2\\Model";
$configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\Data\\";

require_once MAIN_PATH . 'SqlOrganizeMy/Sql/DbMy.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/Entity.php';
require_once MAIN_PATH . 'Model/Schema.php';
require_once MAIN_PATH . 'Data/Persona.php';








