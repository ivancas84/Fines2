<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\DesignacionDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;


add_submenu_page(
    null, 
    'Procesar Planilla Calificación',
    'Procesar Planilla Calificación', 
    'edit_posts', 
    'fines-plugin-ppc2', 
    'ppc2_procesar_planilla_calificacion_page'
  );

  
function ppc2_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();
    $curso = $pdo->cursoById(sanitize_text_field($_GET['curso_id']), PDO::FETCH_ASSOC);

    if(empty($curso)) throw new Exception("No se ha encontrado el curso");
 
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = Tools::excelParse($rawData);

    echo "<h2>Alumnos que no serán evaluados de la Planilla</h2>";
    $alumnosComisionCalificacionPF = ppc_definir_datos_calificaciones($result, $format);

    $alumnosComision = $pdo->alumnosByComision($curso["comision_id"], PDO::FETCH_ASSOC);
    $alumnosComision = Tools::organizeArrayByKey($alumnosComision, "numero_documento");

    $calificaciones = $pdo->calificacionesAprobadasByDisposicionAndDnis($curso["disposicion_id"], array_keys($alumnosComision), PDO::FETCH_ASSOC);
    $calificaciones = Tools::organizeArrayByKey($calificaciones, "numero_documento");

    echo "<h2>Procesando Calificaciones</h2>";
    echo "<pre>";
    $i = 0;
    foreach($alumnosComisionCalificacionPF as $numero_documento => $data) {

        $i++;
        echo "<br><br>Alumno: " . $i . ";<br>";
        print_r($data);

        $alumno_id = ppc_verificar_alumno_comision($pdo, $numero_documento, $alumnosComision, $data, $curso["comision_id"], $curso["plan_id"]);

        ppc_verificar_alumno_calificacion($pdo, $numero_documento, $alumno_id, $calificaciones, $data["calificacion"], $curso["curso_id"], $curso["disposicion_id"]);   
        
        echo "<br><br>";
    }
    echo "</pre>";

    echo "<p>Recibido. Muchas gracias.</p>";
    echo "<p><strong>Los siguientes Alumnos activos figuran desaprobados. Por favor controlar.</strong></p>";
    echo "<p>Si el alumno indicado a continuación está aprobado, por favor, responder este email indicando la nota!</p>";

    $alumnosDesaprobados = [];

    foreach($alumnosComision as $numero_documento => $ac){
        if(
            array_key_exists($numero_documento, $alumnosComisionCalificacionPF)
            || array_key_exists($numero_documento, $calificaciones)
        ) continue;


        array_push($alumnosDesaprobados, $ac);
    }

    usort($alumnosDesaprobados, function ($a, $b) {
        return strcmp($a['apellidos'], $b['apellidos']);
    });


    foreach($alumnosDesaprobados as $ad){
      echo $ad["apellidos"] . ", " . $ad["nombres"] . " DNI " . $ad["numero_documento"] . "<br>";
    }

}

function ppc_insertar_alumno($pdo, $persona_id, $plan_id) {
    $alumno["id"] = uniqid();
    $alumno["persona"] =  $persona_id;
    $alumno["observaciones"] = "Importado desde planilla de calificaciones";
    $alumno["plan"] = $plan_id;

    $insert = $pdo->insertAlumnoArray($alumno);

    echo " - Alumno insertado id ". $alumno["id"];

    return $alumno["id"];
}

function ppc_insertar_alumno_comision($pdo, $alumno_id, $comision_id) {
    $ac = [];
    $ac["id"] = uniqid();
    $ac["alumno"] = $alumno_id;
    $ac["comision"] = $comision_id;
    $ac["estado"] = "Incorporado";
    $ac["observaciones"] = "Importado desde planilla de calificaciones";

    echo " - Alumno incorporado a la comision id " . $ac["id"];
    $pdo->insertAlumnoComisionPrincipalArray($ac);
}

function ppc_insertar_persona($pdo, $data) {
    $data["id"] = uniqid();
    $pdo->insertPersonaArray($data);
    echo " - Persona insertada id ". $data["id"];
    return $data["id"];
}

function ppc_insertar_calificacion($pdo, $alumno_id, $curso_id, $disposicion_id, $nota_final) {
    $calificacion = [];
    $calificacion["id"] = uniqid();
    $calificacion["alumno"] = $alumno_id;
    $calificacion["curso"] = $curso_id;
    $calificacion["nota_final"] = $nota_final;
    $calificacion["disposicion"] = $disposicion_id;
    
    $pdo->insertCalificacionArray($calificacion);

    echo " - Calificacion insertada: " . $calificacion["nota_final"];
}

function ppc_existe_calificacion_aprobada($pdo, $calificacion, $curso_id, $nota) {

    $nota_final = intval($calificacion["nota_final"]);
    if(!empty($nota_final) && $nota_final >= 7) {
        echo " - Ya estaba aprobado con " . $nota_final;
        if($nota_final != intval($nota)) 
            echo " - Calificacion diferente a la de la planilla";
    } else {
        $crec = intval($calificacion["crec"]);
        echo " - Ya estaba aprobado con " . $crec . "C";

        if($crec != intval($nota)) {
            echo " - Calificacion diferente a la de la planilla";
        }
    }
                
    if(empty($calificacion["curso"])){
        $pdo->updatekey("calificacion", "curso", $curso_id, $calificacion["id"]);
        echo " - Curso actualizado";
    } else if($calificacion["curso"] != $curso_id){
        echo " - Aprobado en otro curso";
    } else {
        echo " - Curso correcto";
    }

    echo "<br>";
}

function ppc_no_existe_alumno_en_comision($pdo, $data, $comision_id, $plan_id) {
    $alumno = $pdo->alumnoByNumeroDocumento($data["numero_documento"]);

    if(empty($alumno)){  //no existe el alumno, verificar si existe persona
        $persona = $pdo->personaByNumeroDocumento($data["numero_documento"]);
        
        if(empty($persona)){ //no existe persona, crearla
           $persona_id = ppc_insertar_persona($pdo, $data);
        }

        $alumno_id =  ppc_insertar_alumno($pdo, $persona_id, $plan_id);
    } else {
        $alumno_id = $alumno->id;
        echo " - Alumno ya existe id " . $alumno_id;
    }

    ppc_insertar_alumno_comision($pdo, $alumno_id, $comision_id);

    return $alumno_id;
}

function ppc_verificar_alumno_comision($pdo, $numero_documento, $alumnosComision, $data, $comision_id, $plan_id){
    if(array_key_exists($numero_documento, $alumnosComision)){ //existe alumno en la comision
        $alumno_id = $alumnosComision[$numero_documento]["alumno_id"];
        echo " - Alumno ya existe en la comision id " . $alumno_id;
    } else { //no existe alumno en la comision, verificar si existe el alumno
        $alumno_id = ppc_no_existe_alumno_en_comision($pdo, $data, $comision_id, $plan_id);   
    }

    return $alumno_id;
}



function ppc_verificar_alumno_calificacion($pdo, $numero_documento, $alumno_id, $calificaciones, $nota, $curso_id, $disposicion_id){
    if(array_key_exists($numero_documento, $calificaciones)){ //existe calificacion aprobada
        ppc_existe_calificacion_aprobada($pdo, $calificaciones[$numero_documento], $curso_id, $nota);
    } else { //no existe calificacion aprobada, se inserta la nueva
        ppc_insertar_calificacion($pdo, $alumno_id, $curso_id, $disposicion_id, $nota);
    }
}

function ppc_definir_datos_calificaciones($result, $format) {
    
    $alumnosComisionCalificacionPF = [];

    foreach($result as $row) {
        try {

            if($format == "pf"){ 
                $data = ProgramaFines::parseRowCalificacionPF($row);
                $alumnosComisionCalificacionPF[$data["numero_documento"]] = $data;
            }

        } catch (Exception $e) {
            if($format == "pf"){ 
                echo $row["Apellido, Nombre DNI"] . ": " . $e->getMessage() . "<br><br>";
            }
        }
    } 

    return $alumnosComisionCalificacionPF;
}
