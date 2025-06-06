<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

function esc_html($var){
    return $var;
}  

function admin_url($var){
    return $var;
}  

function esc_url($var){
    return $var;
}   

use \Fines2\TomaDAO;
use \Fines2\CursoDAO;
use \SqlOrganize\Utils\ValueTypesUtils;

use \SqlOrganize\Sql\DbMy;

$calendario_id = CALENDARIO_ID;

$cursos = CursoDAO::CursosActivosConTomasActivasByCalendario($calendario_id);
?>


<h2>Comisiones Consultadas</h2>

<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Sede</th>
            <th>Comisión</th>
            <th>Tramo</th>
            <th>Asignatura</th>
            <th>Docente</th>
            <th>Teléfono</th>
            <th>Fecha Toma</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cursos as $curso): ?>

            <tr>
                <td><?= esc_html($curso->comision_?->sede_?->nombre); ?></td>
                <td><?= esc_html($curso->comision_?->pfid); ?></td>
                <td><?= esc_html($curso->disposicion_?->planificacion_?->getTramo()); ?></td>
                <td><?= esc_html($curso->disposicion_?->asignatura_?->getLabel()); ?></td>
                <td><?= esc_html($curso->toma_activa_?->docente_?->getLabel()); ?></td>
                <td><?= esc_html($curso->toma_activa_?->docente_?->telefono); ?></td>
                <td><?= esc_html($curso->toma_activa_?->fecha_toma?->format('Y-m-d')); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>