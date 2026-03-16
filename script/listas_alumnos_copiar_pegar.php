<?php

/**
 *  Lista rapida de todos los alumnos del calendario actual 
 * */


require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Context;
use Fines2\Model\Calificacion_;
use Fines2\Model\Comision_;
use Fines2\DataAccess\ComisionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;

try {

    /** @var Db */ $dbf = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider =  $dbf->CreateDataProvider();
    /** @var Comision_[] */ $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario" => CALENDARIO_ID_ACTUAL]);
    
    echo "<h3>Comisiones a procesar: " . count($comisiones) . "</h3>";
    
    
    foreach($comisiones as $comision){
        echo "<h2>Procesando " . $comision->pfid . " " . $comision->calendario_->getLabel() . "</h2>";

        /** @var AlumnoComision_[] */ $alumnosComision =  $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id]);

        foreach($alumnosComision as $ac){
            // Evitamos errores si alguna relación no está cargada
            $p = $ac->alumno_->persona_ ?? null;
            
            $nombres     = $p ? $p->nombres          : '';
            $apellidos   = $p ? $p->apellidos        : '';
            $fecha_nac   = $p ? $p->fecha_nacimiento : '';
            $documento   = $p ? $p->numero_documento : '';
            
            $row = [
                'apellidos'          => $apellidos,    
                'nombres'            => $nombres,
                'fecha_nacimiento'   => $fecha_nac,
                'numero_documento'   => $documento,
                'estado'             => $ac->estado ?? '',
                'tiene_dni'          => $ac->alumno_->tiene_dni         ?? '',
                'tiene_constancia'   => $ac->alumno_->tiene_constancia  ?? '',
                'tiene_certificado'  => $ac->alumno_->tiene_certificado ?? '',
                'previas_completas'  => $ac->alumno_->previas_completas ?? '',
                'tiene_partida'      => $ac->alumno_->tiene_partida     ?? '',
                'anio_ingreso'       => $ac->alumno_->anio_ingreso     ?? '',
                'confirmado'         => $ac->alumno_->confirmado_direccion     ?? '',
                'observaciones'      => $ac->alumno_->observaciones     ?? '',
            ];
            
            // Solo la primera vez imprimimos el encabezado
            static $headerPrinted = false;
            if (!$headerPrinted) {
                echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>\n";
                echo "<thead><tr style='background:#e0e0e0;'>";
                foreach (array_keys($row) as $col) {
                    echo "<th>" . htmlspecialchars($col) . "</th>";
                }
                echo "</tr></thead><tbody>\n";
                $headerPrinted = true;
            }
            
            // Fila de datos
            echo "<tr>";
            foreach ($row as $key => $valor) {
                // Manejo especial para fecha_nacimiento
                if ($key === 'fecha_nacimiento' && $valor instanceof DateTime) {
                    $texto = $valor->format('d/m/Y');
                }
                // Otros posibles objetos que puedan aparecer en el futuro
                elseif (is_object($valor)) {
                    $texto = method_exists($valor, '__toString') ? (string)$valor : get_class($valor);
                }
                else {
                    $texto = (string) $valor;
                }

                // null o vacío → -
                $texto = $texto === '' || $texto === ' ' || $texto === null ? '' : $texto;

                echo "<td>" . htmlspecialchars($texto) . "</td>";
            }
            echo "</tr>\n";
        }

        // Cerrar tabla al final del primer foreach de comisiones que tenga alumnos
        if (isset($headerPrinted) && $headerPrinted) {
            echo "</tbody></table><br><br>";
            $headerPrinted = false; // reset para la próxima comisión
        }
    }   


} catch (Exception $ex){
  echo $ex->getMessage();

}