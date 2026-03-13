<?php

use App\Context;
use Fines2\DataAccess\CursoDAO;
use Fines2\DataAccess\TomaDAO;
use Fines2\Model\Persona_;
use Fines2\Model\Toma_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Procesar Comisiones PF',
    'Procesar Comisiones PF', 
    'edit_posts', 
    FINES_PLUGIN.'-pfpc2', 
    'pfpc2_page'
  );

  
function pfpc2_page() {
    wp_page_message();

    /** @var Db */ $db = Context::getFinesDb();

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'pfpc2_form_html.php';
        return;
    }

    $dataText = trim($_POST['data']);

    $procesar_docente = false;
    $dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes"]; // Assuming these are the days
    $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario"=>CALENDARIO_ID_ACTUAL]);

    $pfidComisiones = ValueTypesUtils::arrayOfName($comisiones, "pfid");
    
    echo "<pre>";
    print_r($pfidComisiones);
    echo "</pre>";
    $id_curso = ""; //coloco para que no tire error

    foreach (array_filter(explode(PHP_EOL, $dataText)) as $line) {

        if ($procesar_docente) {
            // Procesar docente
            if (strpos($line, "*") !== false) {
                echo "-- Docente sin designar en curso " . ($comision_pfid ?? '') . " " . ($asignatura_codigo ?? '') . "<br/>";
                echo "<br/>";
                
                $procesar_docente = false;
                continue;
            } elseif (strpos($line, "-") === false) {
                echo "-- Salto de línea, en curso " . ($comision_pfid ?? '') . " " . ($asignatura_codigo ?? '') . "<br/>";
                echo "<br/>";
                
                continue;
            } else {

                $procesar_docente = false;

                $line = str_replace("--", "-", $line); //se han encontrado cuils mal escritos con doble guion

                // Extract CUIL
                preg_match('/\d{2}-\d{8}-\d/', $line, $matches);
                if (!empty($matches)) {
                    $cuil = $matches[0];
                    $cuilParts = explode("-", $cuil);

                    /** @var Persona_ */ $persona = $dataProvider->fetchEntityByUnique("persona", ["numero_documento"=>$cuilParts[1]]);

                    if (empty($persona)) {
                        echo "-- No existe docente " . $cuil . "<br/>";
                        continue;
                    }

                    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
                    $persona->cuil = implode("", $cuilParts);
                    $persona->updateField($modifyQueries,  "cuil");
                    $modifyQueries->process();
                    echo "-- CUIL actualizado " . $persona->cuil . "<br/>";


                    /** @var Toma_ */ $toma = TomaDAO::TomaAprobadaOPendiente($id_curso);
                    if(empty($toma)){
                        try {
                            $toma = new Toma_();
                            $toma->docente = $persona->id;
                            $toma->curso = $id_curso;
                            $toma->fecha_toma = new DateTime();
                            $toma->estado = "Pendiente";
                            $toma->estado_contralor = "Pasar";
                            $toma->tipo_movimiento = "AI";
                            /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
                            $toma->insert($modifyQueries);
                            $modifyQueries->process();
                            echo "-- Toma agregada <br/>";
                        } catch(Exception $ex){
                            echo "-- Error al agregar toma: " . $ex->getMessage() . "<br/>";
                        }
                    }

                    else if($toma->docente != $persona->id) {
                        echo "-- Los cargos no coinciden<br/>";
                        continue;
                    }

                } else {
                    echo "-- No hay match para $line <br>";
                }
                echo "<br/>";

            }

        }

        foreach ($dias as $dia) {
            if (strpos($line, $dia) !== false) {
                $comision_pfid = substr($line, 0, strpos($line, "/"));
                $asignatura_codigo = trim(substr($line, strpos($line, "/") + 1, strpos($line, " ") - strpos($line, "/") - 1));

                if (strlen($asignatura_codigo) > 5)
                    $asignatura_codigo = substr($asignatura_codigo, 0, 5);

                $descripcion_horario = substr($line, strpos($line, $dia));
                if (in_array($comision_pfid, $pfidComisiones)) {
                    echo "Procesando comisión " . $comision_pfid. "<br/>";
                    echo "--Linea: " . $line . "<br/>";
                    $id_curso = CursoDAO::IdCursoByParams($comision_pfid, $asignatura_codigo, CALENDARIO_ID_ACTUAL);
                    if (empty($id_curso)) {
                        echo "-- No existe curso " . $comision_pfid . " " . $asignatura_codigo . "<br>";
                        echo "<br/>";
                        break;
                    }

                    echo "-- Curso existente " . $comision_pfid . " " . $asignatura_codigo . " (" . $id_curso . ")<br>";

                    $modifyQueries = $db->CreateModifyQueries();
                    $modifyQueries->updateKeyValueSqlById("curso", "descripcion_horario", $descripcion_horario, $id_curso);
                    $modifyQueries->execute();
                    echo "-- Horario actualizado " . $descripcion_horario . "<br>";
                    $procesar_docente = true;
                }
                break;
            }
        }
    }
      
}
  