<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

use Fines2\Alumno;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use \Fines2\Comision_;
use \Fines2\DisposicionDAO;
use \Fines2\ModalidadDAO;
use Fines2\Persona_;
use \Fines2\PlanificacionDAO;
use \Fines2\SedeDAO;
use \Fines2\TomaDAO;
use \SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Constancia Alumno Regular', // Título de la página
    'Constancia Alumno Regular', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-car2',  // Slug del submenú
    'car2_page' // Función que muestra la página del submenu
);

function car2_page() {
    wp_page_message();

    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;
  
    $persona = new Persona_();
    $persona->initByUnique(["id" => $persona_id]);

    $alumno = new Alumno_();
    if($persona->_status > -1)
        $alumno->initByUnique(["persona" => $persona_id]);
    

    $alumno_comision = null;
    if($alumno->_status > -1)
        $alumno_comision = AlumnoComisionDAO::ultimaComisionAlumno($alumno->id);

    if(empty($alumno_comision))
        $alumno_comision = new AlumnoComision_();

	
    $notas = "Sede: " . $alumno_comision->comision_?->sede_?->getLabel() ?? "?" . ". Comision:" . $alumno_comision->comision_?->pfid ?? "?" . ". Ultimo periodo cursado: " . $alumno_comision->comision_?->calendario_?->getLabel() ?? "?";
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
	
    include plugin_dir_path(__FILE__) . 'car2_html.php';
}


