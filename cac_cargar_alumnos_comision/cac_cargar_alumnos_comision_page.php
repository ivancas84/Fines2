<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

add_submenu_page(
    null, 
    'Cargar Alumnos Comisión',
    'Cargar Alumnos Comisión', 
    'edit_posts', 
    'fines-plugin-cac', 
    'cac_cargar_alumnos_comision_page'
);

  
function cac_insertar_alumno($pdo, $alumno, $persona_id, $plan_id) {
    $alumno["id"] = uniqid();
    $alumno["persona"] =  $persona_id;
    $alumno["plan"] = $plan_id;

    $insert = $pdo->insertAlumnoAvanzadoArray($alumno);

    echo " - Alumno insertado id ". $alumno["id"];

    return $alumno["id"];
}


function cac_insertar_alumno_comision($pdo, $alumno_id, $comision_id) {
    $ac = [];
    $ac["id"] = uniqid();
    $ac["alumno"] = $alumno_id;
    $ac["comision"] = $comision_id;
    $ac["estado"] = "Incorporado";
    $ac["observaciones"] = "Importado desde lista de alumnos";

    echo " - Alumno incorporado a la comision id " . $ac["id"];
    $pdo->insertAlumnoComisionPrincipalArray($ac);
}


function cac_insertar_persona($pdo, $data) {
    $data["id"] = uniqid();
    $pdo->insertPersonaPrincipalCuilArray($data);
    echo " - Persona insertada id ". $data["id"] . "<br>";
    return $data["id"];
}

function cac_imprimir_comisiones_alumno($pdo, $alumno_id) {
    $comisiones = $pdo->comisionesByAlumno($alumno_id, PDO::FETCH_ASSOC);
    foreach($comisiones as $comision){
        echo " - Existe en comision " . ((empty($comision["pfid"])) ? $comision["division"] : $comision["pfid"]) . " " . $comision["periodo"] . " " . $comision["tramo"] . "<br>";
    }
}

function cac_no_existe_alumno_en_comision($pdo, $data, $comision_id, $plan_id) {
    $alumno = $pdo->alumnoByNumeroDocumento($data["numero_documento"], PDO::FETCH_ASSOC);

    if(empty($alumno)){  //no existe el alumno, verificar si existe persona
        $persona = $pdo->personaByNumeroDocumento($data["numero_documento"], PDO::FETCH_ASSOC);
        
        if(empty($persona)){ //no existe persona, crearla
           $persona_id = cac_insertar_persona($pdo, $data);
        } else { //existe persona, verificar datos
            if(!Tools::nombreParecido($persona, $data))
                throw new Exception("El nombre registrado de la persona es diferente " . $persona["nombres"] . " " . $persona["apellidos"]);
        }

        $alumno_id = cac_insertar_alumno($pdo, $data, $persona_id, $plan_id);
    } else {
        $alumno_id = $alumno->id;
        cac_imprimir_comisiones_alumno($pdo, $alumno_id);
    }

    cac_insertar_alumno_comision($pdo, $alumno_id, $comision_id);

    return $alumno_id;
}


function cac_verificar_alumno_comision($pdo, $alumnosComision, $data, $comision_id, $plan_id){
    $numero_documento = $data["numero_documento"];
    if(array_key_exists($numero_documento, $alumnosComision)){ //existe alumno en la comision
        $alumno_id = $alumnosComision[$numero_documento]["alumno_id"];
        echo " - Alumno ya existe en la comision id " . $alumno_id . "<br>";
        
    } else { //no existe alumno en la comision, verificar si existe el alumno
        $alumno_id = cac_no_existe_alumno_en_comision($pdo, $data, $comision_id, $plan_id);   
    }

    return $alumno_id;
}


function cac_cargar_alumnos_comision_page() {

    $pdo = new PdoFines();
    $comision = $pdo->comisionById(sanitize_text_field($_GET['comision_id']), PDO::FETCH_ASSOC);

    if(empty($comision)) throw new Exception("No se ha encontrado la comision");
 
    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'cac_form.html';
        return;
    }

    echo "<h1>Cargar Alumnos a la Comision</h1>";
    echo "<pre>";
    print_r($comision);

    $rawData = trim($_POST['data']);
    $alumnosData = Tools::excelParseIgnorePrefix($rawData);
    $dnisProcesados = [];
    $alumnosComision = $pdo->alumnosByComision($comision["comision_id"], PDO::FETCH_ASSOC);
    $alumnosComision = Tools::organizeArrayByKey($alumnosComision, "numero_documento");

    $i = 0;

    foreach($alumnosData as $data){
        $i++;
        echo "<br><br>Alumno: " . $i . ";<br>";
        $cuilDni = Tools::cuilDni($data["cuil_dni"]);
        if(empty($cuilDni["dni"])){
            echo $data["apellidos"] . " " . $data["nombres"] . "<br>";
            echo "DNI vacío, no se procesará el alumno<br>";
            continue;
        }

        if(in_array($cuilDni["dni"], $dnisProcesados)){
            echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["dni"] . "<br>";
            echo "DNI ya procesado, no se procesará el alumno<br>";
            continue;
        }
        $dnisProcesados[] = $cuilDni["dni"];

        $data["numero_documento"] = $cuilDni["dni"];
        $data["cuil"] = $cuilDni["cuil"];

        echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["numero_documento"] . "<br>";

        try {
            $alumno_id = cac_verificar_alumno_comision($pdo, $alumnosComision, $data, $comision["comision_id"], $comision["plan_id"]);
        
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";
    }
    echo "</pre>";


}