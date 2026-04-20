<?php


define('APP_VERSION', 'v6');
define('FINES_PLUGIN',"fines6-plugin");
define('MAIN_PATH', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . APP_VERSION . DIRECTORY_SEPARATOR);
define('MAIN_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/' . APP_VERSION);
define("DB_HOST_FINES", "localhost");
define("DB_NAME_FINES", "planfi10_20204");
define("DB_USER_FINES", "root");
define("DB_PASS_FINES", "");

define("DB_HOST_PEDIDOS", "localhost");
define("DB_NAME_PEDIDOS", "planfi10_wp211");
define("DB_USER_PEDIDOS", "root");
define("DB_PASS_PEDIDOS", "");

define("PLANILLA_ID", "202509030000");
define("CALENDARIO_ID_ACTUAL", "202602022327");
define("CALENDARIO_ID_ANTERIOR", "202508131824");
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

define("PATH_UPLOAD_PEDIDOS", "/home/planfi10/domains/planfines2.com.ar/public_html/wp/wp-content/uploads/");
define("PATH_SCHEMA_FINES", __DIR__ . '/../src/Fines2/schema.json');
define("PATH_SCHEMA_PEDIDOS", "path/to/schema.json");
define("PATH_START_API", 1);

define("PF_PERIODO", 6);
define("PF_URL", "https://programafines.ar/inicial/");
















