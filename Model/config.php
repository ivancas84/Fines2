<?php
define("DB_HOST_FINES", "localhost");
define("DB_NAME_FINES", "planfi10_20204");
define("DB_USER_FINES", "root");
define("DB_PASS_FINES", "");
define("RESERVED_ENTITIES", "");
define("RESERVED_ALIAS", "");
define("ID_SOURCE", "field_name");
define("DONT_TREAT_AS_FK", "");
define("LIMIT_TO_FIRST_LEVEL", "");
define("MAIN_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Fines2/");
define("SCHEMA_CLASS_PATH", "C:\\xampp\\htdocs\\Fines2\\Model");
define("SCHEMA_BUILDER_CLASS_PATH", "C:\\xampp\\htdocs\\Fines2\\Model");
define("MODEL_NAMESPACE", "Fines2");
define("ID_NAME", "id");


require_once MAIN_PATH . 'SqlOrganize/Sql/Config.php';
require_once MAIN_PATH . 'SqlOrganizeMy\Sql\DbMy.php';

use SqlOrganize\Sql\Config;
use SqlOrganize\Sql\DbMy;

$config = new Config();
$config->host = DB_HOST_FINES;
$config->dbName = DB_NAME_FINES;
$config->user = DB_USER_FINES;
$config->pass = DB_PASS_FINES;







