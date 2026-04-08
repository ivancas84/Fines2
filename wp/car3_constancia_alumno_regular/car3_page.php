<?php

use App\Context;
use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Constancia Alumno Regular', // Título de la página
    'Constancia Alumno Regular', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN . '-car3',  // Slug del submenú
    'car3_page' // Función que muestra la página del submenu
);

function car3_page() {
    wp_page_message();

    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;
  
    $db = Context::getFinesDb();
    /** @var Alumno_ */ $alumno = $db->CreateDataProvider()->fetchEntityByParams("alumno", ["persona" => $persona_id]);

    if(!$alumno){
        echo "<p>No se encontró un alumno asociado a ese id.</p>";
        die();
    }

    /** @var ?AlumnoComision_ */ $alumno_comision = AlumnoComisionDAO::ultimaComisionAlumno($alumno->id);
    if(empty($alumno_comision)){
        $alumno_comision = new AlumnoComision_();
    }

    $notas = "Sede: " . $alumno_comision->comision_?->sede_?->getLabel() ?? "?" . ". Comision:" . $alumno_comision->comision_?->pfid ?? "?" . ". Ultimo periodo cursado: " . $alumno_comision->comision_?->calendario_?->getLabel() ?? "?";
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
	$anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");
    $presentado = "Quien corresponda";
    $observaciones = "";



    include plugin_dir_path(__FILE__) . 'car3_html.php';
}


