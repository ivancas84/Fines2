<?php

use App\Context;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\Calificacion_;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;

add_submenu_page(
    null, 
    'Transferir Persona',
    'Transferir Persona', 
    'edit_posts', 
    FINES_PLUGIN.'-trp', 
    'trp_page'
);


function trp_page() {
    wp_page_message();

    include plugin_dir_path(__FILE__) . 'trp2_form_html.php';

    if (!isset($_GET['submit']) || empty($_GET['dni_origen']) || empty($_GET['dni_destino'])) {
        return;
    }

    echo "<h3>Procesando...</h3>";
    $dni_origen = $_GET['dni_origen'];
    $dni_destino = $_GET['dni_destino'];

    /** @var Db */ $db = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    /** @var Persona_ */ $persona_origen = $dataProvider->fetchEntityByParams("persona", ["numero_documento" => $dni_origen]);
    /** @var Persona_ */ $persona_destino = $dataProvider->fetchEntityByParams("persona", ["numero_documento" => $dni_destino]);

    if(!$persona_origen){
        echo "<p>No se encontró una persona con el dni $dni_origen</p>";
        return;
    }

    if(!$persona_destino){
        echo "<p>No se encontró una persona con el dni $dni_destino</p>";
        return;
    }

    /** @var ModifyQueries */ $modifyQueries = $db->createModifyQueries();
    $modifyQueries->migrateRelations("detalle_persona", "persona", $persona_origen->id, $persona_destino->id);
    $modifyQueries->migrateRelations("designacion", "persona", $persona_origen->id, $persona_destino->id);
    $modifyQueries->migrateRelations("telefono", "persona", $persona_origen->id, $persona_destino->id);
    $modifyQueries->migrateRelations("email", "persona", $persona_origen->id, $persona_destino->id);

    /** @var ?Alumno_ */ $alumno_destino = $dataProvider->fetchEntityByParams("alumno", ["persona" => $persona_destino->id]);
    /** @var ?Alumno_ */ $alumno_origen = $dataProvider->fetchEntityByParams("alumno", ["persona" => $persona_origen->id]);

    if(!empty($alumno_destino)){
        if(!empty($alumno_origen)){ 
            echo "<p>Se muestran a continuación los datos del alumno origen por si se desea modificar de forma manual al alumno destino</p>";
            echo "<pre>";
            print_r($alumno_origen->toArray());
            echo "</pre>";
            echo "<p>PLAN " . ($alumno_origen->plan_?->getLabel() ?? "No definido") . "</p>";
            echo "<p>RESOLUCIÓN INSCRIPCION " . ($alumno_origen->resolucion_inscripcion_?->getLabel() ?? "No definido") . "</p>";
     
            $alumno_origen->migrateCalificaciones($modifyQueries, $alumno_destino->id);
            $modifyQueries->migrateRelations("alumno_comision", "alumno", $alumno_origen->id, $alumno_destino->id);
            $modifyQueries->deleteSql($alumno_origen);

        } else {
            echo "<p>La persona origen no tiene alumno asociado.</p>";
        }
    } else {
        
        if(!empty($alumno_origen)){
            echo "<p>La persona destino no tiene un alumno asociado. Se asocia el alumno origen a la persona destino</p>";
            $alumno_origen->persona = $persona_destino->id;
            $modifyQueries->updateKeySqlById($alumno_origen, "persona");
        
        } else {
            echo "<p>Ninguna de las dos personas tiene un alumno asociado. No se realiza ninguna acción sobre alumnos.</p>";
        }
    
    }

    $modifyQueries->deleteSql($persona_origen);
    $modifyQueries->process();
    echo "<p>Proceso finalizado. Se ha transferido " . $persona_origen->numero_documento . "</p>";
}
