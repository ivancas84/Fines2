<?php

use Fines7\Core\Plugin;

$value = static function (?array $row, string $key): string {
    if ($row === null) {
        return '';
    }

    return (string) ($row[$key] ?? '');
};

$boolLabel = static function ($value): string {
    return (int) $value === 1 ? 'Si' : 'No';
};

$planLabel = static function (?array $row): string {
    if ($row === null) {
        return '';
    }

    return trim(implode(' - ', array_filter([
        (string) ($row['plan_orientacion'] ?? ''),
        (string) ($row['plan_resolucion'] ?? ''),
    ])));
};

$personaLabel = trim(implode(' ', array_filter([
    $value($persona, 'apellidos'),
    $value($persona, 'nombres'),
])));
?>
<div class="wrap fines7-wrap">
    <h1>Detalle Alumno <?php echo esc_html($personaLabel); ?></h1>

    <p>
        <a class="button" href="<?php echo esc_url(add_query_arg(['page' => Plugin::PERSONAS_SLUG], admin_url('admin.php'))); ?>">
            Volver a Buscar Personas
        </a>
    </p>

    <?php if (!empty($error)) : ?>
        <div class="notice notice-error">
            <p><?php echo esc_html('Fines7 no pudo consultar el detalle de alumno: ' . $error); ?></p>
        </div>
    <?php endif; ?>

    <?php if (empty($error) && $persona !== null) : ?>
        <h2>Persona</h2>
        <div class="fines7-detail-grid">
            <div><strong>Nombres</strong><span><?php echo esc_html($value($persona, 'nombres')); ?></span></div>
            <div><strong>Apellidos</strong><span><?php echo esc_html($value($persona, 'apellidos')); ?></span></div>
            <div><strong>DNI</strong><span><?php echo esc_html($value($persona, 'numero_documento')); ?></span></div>
            <div><strong>CUIL</strong><span><?php echo esc_html($value($persona, 'cuil')); ?></span></div>
            <div><strong>Telefono</strong><span><?php echo esc_html($value($persona, 'telefono')); ?></span></div>
            <div><strong>Email</strong><span><?php echo esc_html($value($persona, 'email')); ?></span></div>
            <div><strong>Email ABC</strong><span><?php echo esc_html($value($persona, 'email_abc')); ?></span></div>
            <div><strong>Nacimiento</strong><span><?php echo esc_html(trim($value($persona, 'dia_nacimiento') . '/' . $value($persona, 'mes_nacimiento') . '/' . $value($persona, 'anio_nacimiento'), '/')); ?></span></div>
            <div><strong>Nacionalidad</strong><span><?php echo esc_html($value($persona, 'nacionalidad')); ?></span></div>
            <div><strong>Domicilio</strong><span><?php echo esc_html($value($persona, 'descripcion_domicilio')); ?></span></div>
            <div><strong>Localidad</strong><span><?php echo esc_html($value($persona, 'localidad')); ?></span></div>
            <div><strong>Partido</strong><span><?php echo esc_html($value($persona, 'partido')); ?></span></div>
        </div>

        <h2>Alumno</h2>
        <?php if ($alumno === null) : ?>
            <p>No hay registro de alumno para esta persona.</p>
        <?php else : ?>
            <div class="fines7-detail-grid">
                <div><strong>Estado de inscripcion</strong><span><?php echo esc_html($value($alumno, 'estado_inscripcion')); ?></span></div>
                <div><strong>Plan</strong><span><?php echo esc_html($planLabel($alumno)); ?></span></div>
                <div><strong>Ingreso</strong><span><?php echo esc_html(trim($value($alumno, 'anio_ingreso') . '-' . $value($alumno, 'semestre_ingreso'), '-')); ?></span></div>
                <div><strong>Inscripcion</strong><span><?php echo esc_html(trim($value($alumno, 'anio_inscripcion') . '-' . $value($alumno, 'semestre_inscripcion'), '-')); ?></span></div>
                <div><strong>Establecimiento</strong><span><?php echo esc_html($value($alumno, 'establecimiento_inscripcion')); ?></span></div>
                <div><strong>Fecha titulacion</strong><span><?php echo esc_html($value($alumno, 'fecha_titulacion')); ?></span></div>
                <div><strong>Tiene DNI</strong><span><?php echo esc_html($boolLabel($alumno['tiene_dni'] ?? 0)); ?></span></div>
                <div><strong>Tiene constancia</strong><span><?php echo esc_html($boolLabel($alumno['tiene_constancia'] ?? 0)); ?></span></div>
                <div><strong>Tiene certificado</strong><span><?php echo esc_html($boolLabel($alumno['tiene_certificado'] ?? 0)); ?></span></div>
                <div><strong>Previas completas</strong><span><?php echo esc_html($boolLabel($alumno['previas_completas'] ?? 0)); ?></span></div>
                <div><strong>Tiene partida</strong><span><?php echo esc_html($boolLabel($alumno['tiene_partida'] ?? 0)); ?></span></div>
                <div><strong>Confirmado direccion</strong><span><?php echo esc_html($boolLabel($alumno['confirmado_direccion'] ?? 0)); ?></span></div>
            </div>

            <?php if (!empty($alumno['observaciones'])) : ?>
                <h3>Observaciones</h3>
                <p><?php echo esc_html($alumno['observaciones']); ?></p>
            <?php endif; ?>

            <h2>Comisiones</h2>
            <?php if (empty($comisiones)) : ?>
                <p>No hay comisiones asignadas.</p>
            <?php else : ?>
                <table class="wp-list-table widefat striped fines7-table">
                    <thead>
                        <tr>
                            <th>Sede</th>
                            <th>PFID</th>
                            <th>Periodo</th>
                            <th>Tramo</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comisiones as $comision) : ?>
                            <tr>
                                <td><?php echo esc_html($comision['sede_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($comision['pfid'] ?: ''); ?></td>
                                <td><?php echo esc_html($comision['calendario_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($comision['tramo_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($planLabel($comision)); ?></td>
                                <td><?php echo esc_html($comision['estado'] ?: ''); ?></td>
                                <td><?php echo esc_html($boolLabel($comision['activo'] ?? 0)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h2>Calificaciones</h2>
            <?php if (empty($calificaciones)) : ?>
                <p>No se encontraron calificaciones para este alumno.</p>
            <?php else : ?>
                <table class="wp-list-table widefat striped fines7-table">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Tramo</th>
                            <th>Plan</th>
                            <th>Nota Final</th>
                            <th>CREC</th>
                            <th>PFID</th>
                            <th>Periodo</th>
                            <th>Docente</th>
                            <th>Observaciones</th>
                            <th>ID Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calificaciones as $calificacion) : ?>
                            <tr>
                                <td><?php echo esc_html($calificacion['asignatura_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['tramo_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($planLabel($calificacion)); ?></td>
                                <td><?php echo esc_html($calificacion['nota_final'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['crec'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['pfid'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['calendario_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['docente_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['observaciones'] ?: ''); ?></td>
                                <td><?php echo esc_html($calificacion['curso'] ?: ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>

        <h2>Detalle</h2>
        <?php if (empty($detalles)) : ?>
            <p>No hay detalles.</p>
        <?php else : ?>
            <table class="wp-list-table widefat striped fines7-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Asunto</th>
                        <th>Descripcion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $detalle) : ?>
                        <?php
                        $descripcion = (string) ($detalle['descripcion'] ?? '');
                        $fileContent = (string) ($detalle['file_content'] ?? '');
                        ?>
                        <tr>
                            <td><?php echo esc_html($detalle['fecha'] ?: ''); ?></td>
                            <td><?php echo esc_html($detalle['tipo'] ?: ''); ?></td>
                            <td><?php echo esc_html($detalle['asunto'] ?: ''); ?></td>
                            <td>
                                <?php if ($fileContent !== '') : ?>
                                    <a href="<?php echo esc_url('https://planfines2.com.ar/upload/' . ltrim($fileContent, '/')); ?>">
                                        <?php echo esc_html($descripcion); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html($descripcion); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
