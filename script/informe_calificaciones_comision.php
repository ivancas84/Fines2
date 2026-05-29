<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\Db;

/** @var Db */ $db = \App\Context::getFinesDb();
/** @var string */ $comision_id = $_REQUEST["comision_id"] ?? null;
/** @var AlumnoComision_[] */ $alumnosComision = AlumnoComisionDAO::alumnosComision($comision_id);

echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; font-family: Arial, sans-serif;">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombres</th>';
echo '<th>Apellidos</th>';
echo '<th>DNI</th>';
echo '<th>Calificaciones Aprobadas</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach($alumnosComision as $ac){
    $alumno = $ac->alumno_;

    // Reestructuración de calificaciones (mantengo esto porque lo usabas)
    $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();
    AlumnoDAO::reestructurarCalificacionesByAlumno($modifyQueries, $alumno);
    $modifyQueries->process();

    $tramo = $alumno->getTramoIngresoShort();

    $calif_lines = [];

    if(!empty($alumno->plan)){
        
        // === Calificaciones del plan actual ===
        $cantidadesPlan = CalificacionDAO::cantidadCalificacionesAprobadasByAlumnoPlanTramo(
            $alumno->id, 
            $alumno->plan, 
            $tramo
        );

        foreach($cantidadesPlan as $c){
            $anio = $c['anio'];
            $sem  = $c['semestre'];
            $cant = $c['cantidad'];
            $calif_lines[] = "<b>{$anio}° {$sem}C:</b> {$cant}";
        }

        // === Calificaciones de otros planes ===
        $cantidadesOtros = CalificacionDAO::cantidadCalificacionesAprobadasByAlumnoNotInPlanTramo(
            $alumno->id, 
            $alumno->plan, 
            $tramo
        );

        $totalOtros = 0;
        foreach($cantidadesOtros as $c){
            $totalOtros += (int)$c['cantidad'];
        }

        if($totalOtros > 0){
            $calif_lines[] = "<b style='color:#0066cc;'>+ {$totalOtros} de otro plan</b>";
        }
    }

    $calif_cell = !empty($calif_lines) 
        ? implode("<br>", $calif_lines) 
        : "Sin calificaciones aprobadas";

    echo '<tr>';
    echo '<td>' . htmlspecialchars($alumno->persona_->getNombres() ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($alumno->persona_->getApellidos() ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($alumno->persona_->numero_documento ?? '') . '</td>';
    echo '<td style="text-align:left; white-space:pre-wrap; line-height:1.4;">' . $calif_cell . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '<p><small>Tabla lista para copiar y pegar en Excel. Cantidades por año y semestre.</small></p>';
?>