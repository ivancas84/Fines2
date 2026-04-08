<?php

use App\Context;
use Fines2\Model\Alumno_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Constancia Pase', // Título de la página
    'Constancia Pase', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN . '-cp3',  // Slug del submenú
    'cp3_page' // Función que muestra la página del submenu
);


function cp3_page() {
    wp_page_message();

    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;
  
    $db = Context::getFinesDb();
    /** @var Alumno_ */ $alumno = $db->CreateDataProvider()->fetchEntityByParams("alumno", ["persona" => $persona_id]);

    if(!$alumno){
        echo "<p>No se encontró un alumno asociado a ese id.</p>";
        die();
    }

    if(!$alumno->plan){
        echo "<p>Alumno sin plan, es necesario completar el plan del alumno para generar la constancia de pase.</p>";
        die();
    }

    $alumno->initializeCalifacionesArrays();

    $aniosCursados = esc_attr(implode(", ", $alumno->AniosCursados));
    $nombres = esc_attr(ValueTypesUtils::toTitleCase($alumno->persona_->nombres));
    $apellidos = esc_attr(mb_strtoupper($alumno->persona_->apellidos));
    $numero_documento = esc_attr($alumno->persona_->numero_documento);
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
	$anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");

    include plugin_dir_path(__FILE__) . 'cp3_html.php';
}


