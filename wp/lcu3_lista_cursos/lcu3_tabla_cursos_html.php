<?php

use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Calificacion_;
use \SqlOrganize\Utils\ValueTypesUtils; 

/** @var AlumnoComision_[] $alumnosComision */ 
/** @var Calificacion_[] $calificaciones */ 
?>

<h2>Comisiones Consultadas</h2>

<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Sede</th>
            <th>Comisión</th>
            <th>Tramo</th>
            <th>Asignatura</th>
            <th>Hs Cat Curso/Disp</th>

            <th>Docente</th>
            <th>Email</th>
            <th>Email ABC</th>
            <th>Teléfono</th>
            <th>Fecha Toma</th>
            <th>Estado</th>
            <th>Planilla Docente</th>
            <th>Estado Planilla</th>
            <th>Aprobados</th>
            <th>Ids</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($cursos as $curso): ?>
        <? $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=> $curso->comision_?->id]);
    $dnis = [];
    foreach($alumnosComision as $ac){
        array_push($dnis, $ac->alumno_->persona_?->numero_documento);
    }
     $calificaciones = CalificacionDAO::calificacionesAprobadasByDisposicionAndDnis($curso->disposicion, $dnis);
     $calificaciones = ValueTypesUtils::dictOfObjByPropertyNames($calificaciones, "alumno");
?>
            <? $detalle_comision_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-ac3&comision_id=' . $curso->comision); ?>
            <? $administrar_tomas_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-administrar-toma-comision&comision_id=' . $curso->comision); ?>
            <? $cargar_planilla_calificacion_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-ppc3&curso_id=' . $curso->id); ?>
            <? $cargar_alumnos_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-cac&comision_id=' . $curso->comision); ?>
            <? $administrar_persona_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-ap3&persona_id=' . $curso->toma_activa_?->docente_?->id); ?>
            <? $lista_alumnos_curso_url = admin_url('admin.php?page=' . FINES_PLUGIN . '-lacu&curso_id=' . $curso->id); ?>

            <tr>
                <td><?= esc_html($curso->comision_?->sede_?->nombre); ?></td>
                <td><?= esc_html($curso->comision_?->pfid); ?></td>
                <td><?= esc_html($curso->disposicion_?->planificacion_?->getTramo()); ?></td>
                <td><?= esc_html($curso->disposicion_?->getLabel()); ?></td>
                <td><?= esc_html($curso->horas_catedra . "/" . $curso->disposicion_?->horas_catedra); ?></td>
                <td><a href="<?=$administrar_persona_url?>"><?= esc_html($curso->toma_activa_?->docente_?->getLabel()); ?></a></td>
                <td><?= esc_html($curso->toma_activa_?->docente_?->email); ?></td>
                <td><?= esc_html($curso->toma_activa_?->docente_?->email_abc); ?></td>
                <td><?= esc_html($curso->toma_activa_?->docente_?->telefono); ?></td>
                <td><?= esc_html($curso->toma_activa_?->fecha_toma?->format('Y-m-d')); ?></td>
                <td><?= esc_html($curso->toma_activa_?->estado); ?><br>
                    <?= esc_html($curso->toma_activa_?->estado_contralor); ?><br></td>
                <td><?= esc_html($curso->toma_activa_?->planilla_docente_?->numero); ?></td>
                <td><?= esc_html($curso->toma_activa_?->estado_planilla ?? "Sin entregar"); ?></td>
                <td><?= esc_html(count($calificaciones)); ?></td>
                <td>Toma <?= esc_html($curso->toma_activa_?->id); ?><br>
                    Curso <?= esc_html($curso->id); ?><br>
                    Comisión <?= esc_html($curso->comision); ?><br>
                    Sede <?= esc_html($curso->comision_?->sede); ?>
                </td>

                <td>
                    <a href="<?= esc_url($detalle_comision_url); ?>" title="Detalle Comisión" class="button"><span  class="dashicons dashicons-tag"></span></a> 
                    <a href="<?= esc_url(admin_url('admin.php?page=' . FINES_PLUGIN . '-rdc&comision_id=' . $curso->comision))?>" class="button" title="Rindex Comisión">
                        <span class="dashicons dashicons-format-aside"></span>
                    </a>
                    <a href="<?= esc_url($administrar_tomas_url); ?>" title="Administrar Tomas" class="button"><span  class="dashicons dashicons-businessperson"></span></a>
                    <a href="<?= esc_url($cargar_planilla_calificacion_url); ?>" title="Cargar Planilla de Calificación" class="button"><span  class="dashicons dashicons-media-spreadsheet"></span></a>
                    <a href="<?= esc_url($cargar_alumnos_url); ?>" title="Cargar Alumnos" class="button"><span  class="dashicons dashicons-groups"></span></a>
                    <a href="<?= esc_url('/scripts/cambiar_estado_planilla.php?toma_id=' . $curso->toma_activa_?->id . '&estado=entregada') ?>" target="_blank" title="Cambiar entregada" class="button"><span  class="dashicons dashicons-yes"></span></a>
                    <a href="<?= esc_url(admin_url('admin.php?page=' . FINES_PLUGIN . '-lacu&curso_id=' . $curso->id))?>" class="button" title="Calificaciones por curso">
                        <span class="dashicons dashicons-paperclip"></span>
                    </a>
                    <a onclick="return confirm('¿Eliminar calificaciones?');" href="<?= esc_url('/scripts/eliminar_calificaciones_curso.php?curso_id=' . $curso->id) ?>" target="_blank" title="Eliminar calificaciones" class="button">
                        <span  class="dashicons dashicons-database-remove"></span>
                    </a>
                    <a onclick="return confirm('¿Eliminar alumnos de la comisión?');" href="<?= esc_url('/scripts/eliminar_alumnos_comision.php?comision_id=' . $curso->comision) ?>" target="_blank" title="Eliminar alumnos comisión" class="button">
                        <span  class="dashicons dashicons-no"></span>
                    </a>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>