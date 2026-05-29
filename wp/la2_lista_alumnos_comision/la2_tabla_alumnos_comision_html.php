<?php
use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;

/** @var AlumnoComision_[] $alumnos_comision */
/** @var Comision_ $comision */
/** @var string $pf_session */

?>

<h2>Cantidad de Alumnos en Comisión <?=$comision->getLabel() ?>: <?=count($alumnos_comision)?></h2>

<table class="wp-list-table widefat striped">   
    <thead>
        <tr>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Cuil1</th>
            <th>Cuil - Dni</th>
            <th>Cuil2</th>
            <th>Cod</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Dia Nac</th>
            <th>Mes Nac</th>
            <th>Año Nac</th>
            <th>Sexo</th>
            <th>Ingreso</th>
            <th>Aprobadas</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($alumnos_comision as $ac): ?>
<?php
/** @var Alumno_ */ $alumno = $ac->alumno_;

$modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();
AlumnoDAO::reestructurarCalificacionesByAlumno($modifyQueries, $alumno);
$modifyQueries->process();

$tramo = $alumno->getTramoIngresoShort();

$calif_lines = [];

if (!empty($alumno->plan)) {
    $cantidadesPlan = CalificacionDAO::cantidadCalificacionesAprobadasByAlumnoPlanTramo($alumno->id, $alumno->plan, $tramo);
    foreach ($cantidadesPlan as $c) {
        $calif_lines[] = "<b>" . $c['anio'] . "° " . $c['semestre'] . "C:</b> " . $c['cantidad'];
    }

    $cantidadesOtros = CalificacionDAO::cantidadCalificacionesAprobadasByAlumnoNotInPlanTramo($alumno->id, $alumno->plan, $tramo);
    $totalOtros = array_sum(array_column($cantidadesOtros, 'cantidad') ?: []);

    if ($totalOtros > 0) {
        $calif_lines[] = "<b style='color:#0066cc;'>+ " . $totalOtros . " otro plan</b>";
    }
}

$calif_cell = !empty($calif_lines) ? trim(implode("<br>", $calif_lines)) : "<em>Sin calificaciones</em>";
?>
            <tr>
                <td><?=esc_html($alumno->persona_?->apellidos ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->nombres ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->cuil1 ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->getCuilDni() ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->cuil2 ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->codigo_area ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->telefono ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->email ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->dia_nacimiento ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->mes_nacimiento ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->anio_nacimiento ?? "?")?></td>
                <td><?=esc_html($alumno->persona_?->getSexo() ?? "?")?></td>
                <td><?=esc_html($alumno->getTramoIngreso() ?? "?")?></td>
                
                <td style="text-align:left; white-space:pre-wrap; line-height:1.4; min-width:220px; padding:8px;"><?= $calif_cell ?></td>
                <td><?=boolToSiNo($ac->activo)?></td>

                <td>
                    <a href="<?=esc_url(admin_url('admin.php?page=fines6-plugin-aa4&persona_id='.$ac->alumno_->persona))?>" title="Detalle">
                        <button type="button"><span class="dashicons dashicons-edit"></span></button>
                    </a>

                    <form method="post" action="<?=MAIN_URL?>script/eliminar_alumno_comision.php" style="display:inline;" onsubmit="return confirm('Eliminar alumno de la comisión?');">
                        <button type="submit" name="alumno_comision_id" value="<?=esc_attr($ac->id)?>" title="Eliminar">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </form>

                    <?php if(!empty($pf_session)): ?>
                        <form method="post" action="<?=MAIN_URL?>script/pf_agregar_alumno.php" style="display:inline;" onsubmit="return confirm('Desea agregar alumno a la comisión de programafines?');">
                            <input type="hidden" name="nombres" value="<?=esc_attr($alumno->persona_?->nombres??'')?>"/>
                            <input type="hidden" name="apellidos" value="<?=esc_attr($alumno->persona_?->apellidos??'')?>"/>
                            <input type="hidden" name="cuil1" value="<?=esc_attr($alumno->persona_?->cuil1??'')?>"/>
                            <input type="hidden" name="numero_documento" value="<?=esc_attr($alumno->persona_?->numero_documento??'')?>"/>
                            <input type="hidden" name="cuil2" value="<?=esc_attr($alumno->persona_?->cuil2??'')?>"/>
                            <input type="hidden" name="alumno_comision_id" value="<?=esc_attr($ac->id)?>"/>
                            <button type="submit" title="Agregar a Programafines">
                                <span class="dashicons dashicons-plus"></span>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>

<p><small>Calificaciones aprobadas agrupadas por año y semestre.</small></p>