<?php

use App\Context;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use SqlOrganize\Utils\ValueTypesUtils;

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

add_submenu_page(
    null, 
    'Constancia de certificado de título en trámite completo',
    'Constancia de certificado de título en trámite completo', 
    'edit_posts', 
    'fines-plugin-ccc', 
    'ccc_constancia_certificado_completo_page'
);


function ccc_constancia_certificado_completo_page() {
    wp_page_message();

    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;

    
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

    $notas = $alumno_comision->comision_?->getLabel() ?? "Sin comision activa"; 
    $anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
    $presentado = "Quien corresponda";
    $observaciones = "";
	
    include plugin_dir_path(__FILE__) . 'ccc_constancia_certificado_completo_page_html.php';
}


