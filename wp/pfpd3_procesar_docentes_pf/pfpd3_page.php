<?php

use Fines2\DataAccess\CursoDAO;
use Fines2\DataAccess\PersonaDAO;
use Fines2\Model\Comision_;
use Fines2\Model\Curso_;
use Fines2\Model\Persona_;
use Fines2\Model\Toma_;
use ProgramaFines\Utils\PfUtils;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Procesar Docentes PF',
    'Procesar Docentes PF', 
    'edit_posts', 
    FINES_PLUGIN.'-pdpf3', 
    'pdpf3_page'
  );

  
function pdpf3_page() {
    wp_page_message();

    $db = \App\Context::getFinesDb();

    $dataProvider = $db->CreateDataProvider();

    $docentesOtrosCensInsertados = 0;
    $docentesOtrosCensExistentes = 0;
    $docentesOtrosCensModificados = 0;
    $docentesSinDesignarOtrosCens = 0;

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'pfpd3_form_html.php';
        return;
    }

    $rawData = trim($_POST['data']);
    $result = PfUtils::excelDocentesParse($rawData);

    for($i = 0; $i < count($result); $i++){
        try {
            $c462 = false;

            if($result[$i]["cens"] == "462") {
                $c462 = true;
                echo "<h2>Procesando fila CENS 462 ". $i . "</h2>";
                echo "<pre>";
                print_r($result[$i]);
                echo "</pre>";
            }

            if(empty($result[$i]["numero_documento"])){
                if($c462) echo "--Docente sin designar<br>";
                else $docentesSinDesignarOtrosCens++;
                continue;
            }

            /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
            /** @var Persona_ */ $persona = PersonaDAO::createPersonaByUnique($result[$i]);
            $modifyQueries->persistSqlByStatus($persona);
            if ($persona->_status === 1) {
                if($c462) echo "--Docente existente<br>";
                else $docentesOtrosCensExistentes++;
            } else if ($persona->_status === 0 ){
                if($c462) echo "--Docente modificado<br>";
                else $docentesOtrosCensModificados++;
                $modifyQueries->updateSql($persona);
            } else {
                if($c462) echo "--Docente insertado<br>";
                else $docentesOtrosCensInsertados;
                $modifyQueries->insertSql($persona);
            }

            if($c462){ 
                /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["pfid" => $result[$i]["comision"], "calendario" => CALENDARIO_ID_ACTUAL]);
                if(empty($comision)) {
                    echo "-- No se encontró la comisión " . $result[$i]["comision"] . "<br>";
                    continue;
                }
                /** @var Curso_[] */ $cursosConTomasActivas = CursoDAO::CursosConTomasAprobadasYPendientesByComision($comision->id);
                if(empty($cursosConTomasActivas)) {
                    echo "-- No se encontraron cursos con toma activa para la comisión " . $comision->id . "<br>";
                    continue;
                }

                $codigo = ValueTypesUtils::substringBetween($result[$i]["asignatura"], "(", ")");
                if(empty($codigo)) {
                    echo "-- No se encontró el código de la asignatura<br>";
                    continue;
                }

                foreach($cursosConTomasActivas as $curso) {
                    
                    $codigos = explode(",", $curso->disposicion_->asignatura_->codigo);
                    if(!in_array($codigo, $codigos)) {
                        continue;
                    }
                    if(empty($curso->toma_activa)){
                        $toma = new Toma_();
                        $toma->setFk("docente", $persona);
                        $toma->setFk("curso", $curso);
                        $toma->fecha_toma = new DateTime;
                        $toma->estado = "Pendiente";
                        $toma->tipo_movimiento = "AI";
                        $toma->estado_contralor = "Pasar";
                        $modifyQueries->insertSql($toma);
                        echo "-- Toma creada para el curso " . $curso->id . " " .  $curso->disposicion_->asignatura_->nombre . "<br>";
                    } else if ($curso->toma_activa_->docente == $persona->id) {
                        echo "-- Toma ya existe para el curso " . $curso->id . " " .  $curso->disposicion_->asignatura_->nombre . "<br>";
                    } else {
                        echo "-- Toma ya existe para el curso " . $curso->id . " " .  $curso->disposicion_->asignatura_->nombre . " pero con otro docente. No se creará una nueva toma.<br>";
                    }
                }
                $modifyQueries->process();

            }
        } catch (Exception $e) {
            echo "--Error procesando fila " . $i . ": " . $e->getMessage() . "<br>";      
        }
           
    }

    echo "<br><br>";
    echo "Docentes de otros CENS insertados: " . $docentesOtrosCensInsertados . "<br>";
    echo "Docentes de otros CENS existentes: " . $docentesOtrosCensExistentes . "<br>";
    echo "Docentes de otros CENS modificados: " . $docentesOtrosCensModificados . "<br>";
    echo "Docentes sin designar de otros CENS: " . $docentesSinDesignarOtrosCens . "<br>";
      
}
  