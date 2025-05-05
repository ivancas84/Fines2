<?php

add_submenu_page(
    null, 
    'Procesar Planilla Calificación',
    'Procesar Planilla Calificación', 
    'edit_posts', 
    'fines-plugin-ppc', 
    'ppc_procesar_planilla_calificacion_page'
  );


require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

function ppc_insertar_alumno($pdo, $data, $persona_id) {
    $alumno["id"] = uniqid();
    $alumno["persona"] =  $persona_id;
    $alumno["observaciones"] = "Importado desde planilla de calificaciones";
    $alumno["plan"] = $data["plan_id"];

    $insert = $pdo->insertAlumnoPrincipalArray($alumno);
    if(!$insert) throw new Exception("No se pudo insertar al alumno: " . $stmt->errorInfo());

    return $alumno["id"];
}

function ppc_insertar_alumno_comision($pdo, $alumno_id, $comision_id) {
    $alumno["id"] = uniqid();
    $alumno["alumno"] = $alumno_id;
    $alumno["comision"] = $comision_id;
    $alumno["estado"] = "Ingresante";
    $alumno["observaciones"] = "Importado desde planilla de calificaciones";

    $insert = $pdo->insertAlumnoComisionPrincipalArray($alumno);
    if(!$insert) throw new Exception("No se pudo insertar al alumno: " . $stmt->errorInfo());
}

function ppc_insertar_calificacion($pdo, $alumno_id, $curso_id, $nota_final, $crec) {
    $alumno["id"] = uniqid();
    $alumno["alumno"] = $alumno_id;
    $alumno["curso"] = $curso_id;
    $alumno["nota_final"] = $nota_final;
    $alumno["crec"] = $crec;

    $insert = $pdo->insertAlumnoComisionPrincipalArray($alumno);
    if(!$insert) throw new Exception("No se pudo insertar al alumno: " . $stmt->errorInfo());
}

function ppc_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();
	$curso_id = isset($_GET['curso_id']) ? sanitize_text_field($_GET['curso_id']) : throw new Exception("No se ha especificado el curso_id");
    
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc_form.html';
    }
    else  {

        $rawData = trim($_POST['data']);
        $format = $_POST['format'];
        $result = Tools::excelParse($rawData);

        $alumnosComisionCalificacionPF = [];

        foreach($result as $row) {
            echo print_r($row, true);

            try {

                if($format == "pf"){ 
                    $data = ProgramaFines::parseRowCalificacionPF($row);
                    $numero_documento = $data["numero_documento"];
                    $alumnosComisionCalificacionPF[$numero_documento] = $data;
                }

            } catch (Exception $e) {
                echo " - " . $e->getMessage() . "<br>";
            }
        } 

        $curso = $pdo->cursoById($curso_id);


        $alumnosComision = $pdo->alumnosByComision($curso["comision_id"], PDO::FETCH_ASSOC);
        $alumnosComision = Tools::organizeArrayByKey($alumnosComision, "numero_documento");

        $calificaciones = $pdo->calificacionesAprobadasByDisposicionAndDnis($curso["disposicion_id"], array_keys($alumnosComision), PDO::FETCH_ASSOC);
        $calificaciones = Tools::organizeArrayByKey($calificaciones, "numero_documento");



        foreach($alumnosComisionCalificacionPF as $numero_documento => $data) {

            if(array_key_exists($numero_documento, $calificaciones)){ //no existe calificacion aprobada
                echo " - Estaba aprobado con: " . $calificaciones[$numero_documento]["nota_final"] . " " . $calificaciones["crec"] . "C<br>";
                
                if(empty($calificaciones[$numero_documento]["curso"])){
                    $pdo->updatekey("calificacion", "curso", $curso["id"], $calificaciones[$numero_documento]["id"]);
                    echo " - Curso actualizado<br>";
                } else if($calificaciones[$numero_documento]["curso"] != $curso["id"]){
                        echo " - Aprobado en otro curso<br>";
                }
                continue;
            }

            if(!array_key_exists($numero_documento, $alumnosComision)){ //no existe alumno en la comision
                $alumno = $pdo->alumnoByNumeroDocumento($data["numero_documento"]);

                if(empty($alumno)){  //no existe el alumno, verificar si existe persona
                    $persona = $pdo->personaByNumeroDocumento($numero_documento);
                    
                    if(empty($persona)){ //no existe persona, crearla
                        $persona["id"] = uniqid();
                        $insert = $pdo->insertPersonaPrincipalArray($data);
                        if(!$insert) throw new Exception("No se pudo insertar a la persona: " . $stmt->errorInfo());
                        echo " - persona insertada";
                    }

                    $alumno_id = ppc_insertar_alumno($pdo, $data, $persona["id"]);

                    echo " - alumno insertado";
                }

            }

            ppc_insertar_alumno_comision($pdo, $alumno_id, $curso["comision_id"]);
        }





        $numerosDocumentoPF = array_keys($alumnosComisionCalificacionPF);
    
        $calificacionesAprobadasDisposicion = $pdo->calificacionesByDisposicionAndDnis($curso["disposicion_id"], PDO::FETCH_ASSOC);

        $calificacionesCurso = $pdo->calificacionesByCurso($curso["disposicion_id"], PDO::FETCH_ASSOC);
    
    }

}