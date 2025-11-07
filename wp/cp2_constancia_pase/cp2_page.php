<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

use App\Context;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\Calificacion_;
use Fines2\CalificacionDAO;
use Fines2\Persona_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Constancia Pase 2', // Título de la página
    'Constancia Pase 2', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-cp2',  // Slug del submenú
    'cp2_page' // Función que muestra la página del submenu
);

function cp2_page() {
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

    $calificacionesAprobadas_ = [];
    $calificacionesDesaprobadas_ = [];
    /** @var Calificacion_[] */ $calificaciones = CalificacionDAO::calificacionesByAlumnoPlanTramo($alumno->id, $alumno->plan, $alumno->getTramoIngresoShort());
    foreach($calificaciones as $c){
        if($c->getNotaAprobada() != null){
            $calificacionesAprobadas_[] = $c;
        } else {
            $calificacionesDesaprobadas_[] = $c;
        }
    }


    $calificacionesAprobadas = count($calificacionesAprobadas_ );
    $calificacionesDesaprobadas = count($calificacionesDesaprobadas_ );
    $orientacion = $alumno->plan_->orientacion;
    $resolucion = $alumno->plan_->resolucion;
    $aniosCursados = esc_attr(implode(", ", CalificacionDAO::getAniosCursados($calificaciones)));
    $nombres = esc_attr(ValueTypesUtils::toTitleCase($alumno->persona_->nombres));
    $apellidos = esc_attr(mb_strtoupper($alumno->persona_->apellidos));
    $numero_documento = esc_attr($alumno->persona_->numero_documento);
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
	$anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");

    include plugin_dir_path(__FILE__) . 'cp2_html.php';
}


