<?php



require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\AlumnoDAO;
use Fines2\AlumnoComision_;
use Fines2\Curso_;
use Fines2\CalificacionDAO;
use Fines2\DesignacionDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Lista alumnos curso', // Título de la página
    'Lista alumnos curso', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-lac',  // Slug del submenú
    'lacu_lista_alumnos_curso_page' // Función que muestra la página del submenu
);

function lacu_lista_alumnos_curso_page() {
    wp_page_message();

    $curso_id = $_GET["curso_id"];
    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();
    /** @var Curso_ */$curso = $dataProvider->fetchEntityByParams("curso", ["id"=>$curso_id]);
    /** @var AlumnoComision_[] */ $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=> $curso->comision_?->id]);
    $dnis = [];
    foreach($alumnosComision as $ac){
        array_push($dnis, $ac->alumno_->persona_?->numero_documento);
    }
     /** @var Calificacion_[] */ $calificaciones = CalificacionDAO::calificacionesAprobadasByDisposicionAndDnis($curso->disposicion, $dnis);
     $calificaciones = ValueTypesUtils::dictOfObjByPropertyNames($calificaciones, "alumno");

    foreach($alumnosComision as $ac){
        if(in_array($ac->alumno, $calificaciones)){
            $ac->_label = $calificaciones[$ac->alumno]->getNotaAprobada();
        }
    }

    echo "<h3> Alumnos curso " . $curso->getLabel() . "</h3>";
    if(empty($alumnosComision)){
        echo "El curso no tiene alumnos cargados";
    } else {
        include plugin_dir_path(__FILE__) . 'lacu_table_html.php';
    }
}