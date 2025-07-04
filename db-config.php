<?php


require_once __DIR__ . '/sqlo-config.php';

require_once MAIN_PATH . 'SqlOrganize/Sql/requires.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Sql/requires.php';
require_once MAIN_PATH . 'schema.php';
require_once MAIN_PATH . 'schema_.php';
require_once MAIN_PATH . 'DataAccess/AlumnoComisionDAO.php';
require_once MAIN_PATH . 'DataAccess/AlumnoDAO.php';
require_once MAIN_PATH . 'DataAccess/CalificacionDAO.php';
require_once MAIN_PATH . 'DataAccess/ComisionDAO.php';
require_once MAIN_PATH . 'DataAccess/CursoDAO.php';
require_once MAIN_PATH . 'DataAccess/DesignacionDAO.php';
require_once MAIN_PATH . 'DataAccess/PersonaDAO.php';
require_once MAIN_PATH . 'DataAccess/TomaDAO.php';


use SqlOrganize\Sql\DbMy;
use Fines2\Schema_;
use Fines2\MainConfig;

DbMy::initInstance(MainConfig::getConfigDb(), Schema_::getEntities());

require_once MAIN_PATH . 'Model_/AlumnoComision_.php';
require_once MAIN_PATH . 'Model_/Calendario_.php';
require_once MAIN_PATH . 'Model_/Calificacion_.php';
require_once MAIN_PATH . 'Model_/Cargo_.php';
require_once MAIN_PATH . 'Model_/CentroEducativo_.php';
require_once MAIN_PATH . 'Model_/Comision_.php';
require_once MAIN_PATH . 'Model_/Curso_.php';
require_once MAIN_PATH . 'Model_/Designacion_.php';
require_once MAIN_PATH . 'Model_/Disposicion_.php';
require_once MAIN_PATH . 'Model_/Domicilio_.php';
require_once MAIN_PATH . 'Model_/Modalidad_.php';
require_once MAIN_PATH . 'Model_/Persona_.php';
require_once MAIN_PATH . 'Model_/Planificacion_.php';
require_once MAIN_PATH . 'Model_/Sede_.php';
