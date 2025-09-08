<?php


require_once __DIR__ . '/main-config.php';


require_once MAIN_PATH . 'schema.php';
require_once MAIN_PATH . 'schema_.php';
require_once MAIN_PATH . 'DataAccess/AlumnoComisionDAO.php';
require_once MAIN_PATH . 'DataAccess/AlumnoDAO.php';
require_once MAIN_PATH . 'DataAccess/CalendarioDAO.php';
require_once MAIN_PATH . 'DataAccess/CalificacionDAO.php';
require_once MAIN_PATH . 'DataAccess/ComisionDAO.php';
require_once MAIN_PATH . 'DataAccess/CursoDAO.php';
require_once MAIN_PATH . 'DataAccess/DesignacionDAO.php';
require_once MAIN_PATH . 'DataAccess/DisposicionDAO.php';
require_once MAIN_PATH . 'DataAccess/ModalidadDAO.php';
require_once MAIN_PATH . 'DataAccess/PersonaDAO.php';
require_once MAIN_PATH . 'DataAccess/PlanificacionDAO.php';
require_once MAIN_PATH . 'DataAccess/SedeDAO.php';
require_once MAIN_PATH . 'DataAccess/TomaDAO.php';

require_once MAIN_PATH . 'ProgramaFines/PfDAO.php';
require_once MAIN_PATH . 'ProgramaFines/PfUtils.php';
\App\Context::initFinesDb();
