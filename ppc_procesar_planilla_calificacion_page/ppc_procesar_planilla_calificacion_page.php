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

function ppc_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();
	$curso_id = isset($_GET['curso_id']) ? sanitize_text_field($_GET['curso_id']) : '';
    $curso = $pdo->cursoById($curso_id);
    
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc_form.html';
    }
    else  {

        $rawData = trim($_POST['data']);
        $format = $_POST['format'];
        $result = Tools::excelParse($rawData);

        foreach($result as $row) {
            $data = array();

            try {

                if($format == "pf"){
                    foreach($row as $key => $value) {
                        if(str_contains($key, "Nombre")){
                            $data = array_merge($data, Tools::parseFirstColumnCalificacionPF($value));
                        } else if(str_contains($key, "Final")){
                            $data["calificacion"] = Tools::formatCalificacionValue($value);
                        }
                    }

                    $nombres = $data["nombres"];
                    $apellidos = $data["apellidos"];
                    $numero_documento = $data["numero_documento"];
                    $calificacion = $data["calificacion"];
                    if(empty($nombres) || empty($numero_documento) || empty($calificacion)){
                        throw new Exception("Datos incompletos en la fila.");
                    }

                    //verificar si existe el alumno
                    $alumno = $pdo->alumnoByNumeroDocumento($numero_documento);

                    if(empty($alumno)){
                        //no existe el alumno, verificar si existe persona
                        $persona = $pdo->personaByNumeroDocumento($numero_documento);
                        if(empty($persona)){
                            $persona["id"] = uniqid();
                            $insert = $pdo->insertPersonaPrincipalArray($data);
                            if(!$insert) throw new Exception("No se pudo insertar la persona: " . $stmt->errorInfo());
                        }

                        $alumno["id"] = uniqid();
                        $alumno["persona"] = $persona["id"];
                        $alumno["observaciones"] = "Importado desde planilla de calificaciones";
                        $alumno["plan"] = $data["plan_id"];

                        $pdo->insertAlumnoPrincipalArray($alumno);
                    }
                    

                }

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "<br>";
            }
        } 

    }

}