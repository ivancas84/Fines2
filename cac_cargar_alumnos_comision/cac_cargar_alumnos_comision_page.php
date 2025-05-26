<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/AlumnoDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PersonaDAO.php');

add_submenu_page(
    null, 
    'Cargar Alumnos Comisión',
    'Cargar Alumnos Comisión', 
    'edit_posts', 
    'fines-plugin-cac', 
    'cac_cargar_alumnos_comision_page'
);

  
function cac_insertar_alumno($alumno, $persona_id, $plan_id) {
    $alumno["id"] = uniqid();
    $alumno["persona"] =  $persona_id;
    $alumno["plan"] = $plan_id;

    AlumnoDAO::insertAlumnoArray($alumno);
    echo " - Alumno insertado id ". $alumno["id"];

    return $alumno["id"];
}


function cac_insertar_alumno_comision($pdo, $alumno_id, $comision_id) {
    $ac = [];
    $ac["id"] = uniqid();
    $ac["alumno"] = $alumno_id;
    $ac["comision"] = $comision_id;
    $ac["estado"] = "Ingresante";
    $ac["observaciones"] = "Importado desde lista de alumnos";

    echo " - Alumno ingresante a la comision id " . $ac["id"];
    $pdo->insertAlumnoComisionPrincipalArray($ac);
}


function cac_insertar_persona($data) {
    $data["id"] = uniqid();
    PersonaDAO::insertPersonaArray($data);
    echo " - Persona insertada id ". $data["id"] . "<br>";
    return $data["id"];
}



function cac_imprimir_comisiones_alumno($pdo, $alumno_id) {
    $comisiones = $pdo->comisionesByAlumno($alumno_id, PDO::FETCH_ASSOC);
    foreach($comisiones as $comision){
        echo " - Existe en comision " . ((empty($comision["pfid"])) ? $comision["division"] : $comision["pfid"]) . " " . $comision["periodo"] . " " . $comision["tramo"] . "<br>";
    }
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
    
    print_r($comision);

    $rawData = trim($_POST['data']);
    $alumnosData = Tools::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de alumnos a procesar ". count($alumnosData) . "</h2>";
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

            $persona = PersonaDAO::personaByNumeroDocumento($data["numero_documento"], PDO::FETCH_ASSOC);
                
            if(empty($persona)){ //no existe persona, crearla
                $data["persona_id"] = cac_insertar_persona($data);
            } else { //existe persona, verificar datos
                if(!Tools::nombreParecido($persona, $data))
                    throw new Exception("El nombre registrado de la persona es diferente " . $persona["nombres"] . " " . $persona["apellidos"]);
                $data["persona_id"] = $persona["id"];
                $actualizaciones = PersonaDAO::compareAndUpdatePersonaArray($persona, $data);
                if(empty($actualizaciones)){
                    echo " - Persona ya existe, no se actualiza id ". $data["persona_id"] . "<br>";
                } else {
                    echo " - Persona actualizada id ". $data["persona_id"] . "<br>";
                    echo "<pre>";
                    print_r($actualizaciones);
                    echo "</pre>";        
                }
                   
            }

            $alumno = AlumnoDAO::alumnoByNumeroDocumento($data["numero_documento"], PDO::FETCH_ASSOC);
        
            if(empty($alumno)){  //no existe el alumno, verificar si existe persona
                $data["alumno_id"] = cac_insertar_alumno($data, $data["persona_id"], $plan_id);
            } else {
                $actualizaciones = AlumnoDAO::compareAndUpdateAlumnoArray($alumno, $data);
                $data["alumno_id"] = $alumno["id"];
                $actualizaciones = PersonaDAO::compareAndUpdatePersonaArray($persona, $data);
                if(empty($actualizaciones)){
                    echo " - Alumno ya existe, no se actualiza id ". $data["alumno_id"] . "<br>";
                } else {
                    echo " - Alumno actualizado id ". $data["alumno_id"] . "<br>";
                    echo "<pre>";
                    print_r($actualizaciones);
                    echo "</pre>";        
                }
                cac_imprimir_comisiones_alumno($pdo, $data["alumno_id"]);
            }

            if(array_key_exists($data["numero_documento"], $alumnosComision)){ //existe alumno en la comision
                echo " - Alumno ya existe en la comision<br>";
            } else { 
                cac_insertar_alumno_comision($pdo, $data["alumno_id"], $comision["comision_id"]);
            }
        
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";
    }
    echo "</pre>";


}