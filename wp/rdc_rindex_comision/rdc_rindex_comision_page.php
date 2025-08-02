<?php



require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\AlumnoComision_;
use Fines2\CalificacionDAO;
use Fines2\Comision_;
use Fines2\Curso_;
use Fines2\CursoDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;



add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Rindex Comisión', // Título de la página
    'Rindex Comisión', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-rdc',  // Slug del submenú
    'rdc_rindex_comision_page' // Función que muestra la página del submenu
);

function rdc_rindex_comision_page() {
    wp_page_message();

    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();

    $comision_id = $_GET["comision_id"];

    /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["id" => $comision_id]);
    /** @var AlumnoComision_[] */ $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=>$comision_id, "activo"=>true]);
    /** @var string[] */ $dnis = [];

    foreach($alumnosComision as $alumnoComision) {
        array_push($dnis, $alumnoComision->alumno_?->persona_?->numero_documento);
    }
    
    /** @var Curso_[] */ $cursos =CursoDAO::CursosConTomasActivasByComision($comision_id);
    $informe = [];
    for($i = 0; $i < count($cursos); $i++){
        $curso = $cursos[$i];
        $calificaciones = CalificacionDAO::calificacionesAprobadasByDisposicionAndDnis($curso->disposicion, $dnis);
        $calificaciones = ValueTypesUtils::dictOfObjByPropertyNames( $calificaciones, "alumno" );
        array_push($informe, [
            "asignatura" => $curso->disposicion_?->asignatura_?->getLabel() ?? "?",
            "tramo" => $curso->disposicion_?->planificacion_?->getTramo() ?? "?",
            "docente" => $curso->toma_activa_?->docente_?->getLabel() ?? "?",
        ]);

        foreach($alumnosComision as &$alumnoComision){
            if(array_key_exists($alumnoComision->alumno, $calificaciones)) {
                $alumnoComision->notas[$i] = $calificaciones[$alumnoComision->alumno]->getNotaAprobada();
            } else {
                $alumnoComision->notas[$i] = "";
            }
        }

    }

    

    include plugin_dir_path(__FILE__) . 'rdc_tabla_html.php';
}