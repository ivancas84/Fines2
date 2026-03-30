<?php

use App\Context;
use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Constancia de certificado de título en trámite completo',
    'Constancia de certificado de título en trámite completo', 
    'edit_posts', 
    FINES_PLUGIN.'-ccc2', 
    'ccc2_page'
);


function ccc2_page() {
    wp_page_message();

    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;

    
    /** @var Db */ $db = Context::getFinesDb();
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
	
    include plugin_dir_path(__FILE__) . 'ccc2_page_html.php';
}


