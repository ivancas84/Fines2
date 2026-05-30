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

    <?php if ($notice === 'persona_guardada') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Datos de persona guardados.</p>
        </div>
    <?php elseif ($notice === 'alumno_guardado') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Datos de alumno guardados.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($error) && $persona !== null) : ?>
        <h2>Persona</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="fines7-edit-form">
            <?php wp_nonce_field('fines7_update_persona_' . $persona['id']); ?>
            <input type="hidden" name="action" value="fines7_update_persona">
            <input type="hidden" name="persona_id" value="<?php echo esc_attr($persona['id']); ?>">

            <div class="fines7-form-grid">
                <label>Nombres <input type="text" name="nombres" value="<?php echo esc_attr($value($persona, 'nombres')); ?>" required></label>
                <label>Apellidos <input type="text" name="apellidos" value="<?php echo esc_attr($value($persona, 'apellidos')); ?>"></label>
                <label>DNI <input type="text" name="numero_documento" value="<?php echo esc_attr($value($persona, 'numero_documento')); ?>" required></label>
                <label>CUIL <input type="text" name="cuil" value="<?php echo esc_attr($value($persona, 'cuil')); ?>"></label>
                <label>CUIL prefijo <input type="number" name="cuil1" value="<?php echo esc_attr($value($persona, 'cuil1')); ?>"></label>
                <label>CUIL sufijo <input type="number" name="cuil2" value="<?php echo esc_attr($value($persona, 'cuil2')); ?>"></label>
                <label>
                    Sexo
                    <select name="sexo">
                        <option value="">Seleccione...</option>
                        <option value="1" <?php selected($value($persona, 'sexo'), '1'); ?>>Masculino</option>
                        <option value="2" <?php selected($value($persona, 'sexo'), '2'); ?>>Femenino</option>
                        <option value="3" <?php selected($value($persona, 'sexo'), '3'); ?>>No binario</option>
                    </select>
                </label>
                <label>Dia nacimiento <input type="number" name="dia_nacimiento" min="1" max="31" value="<?php echo esc_attr($value($persona, 'dia_nacimiento')); ?>"></label>
                <label>Mes nacimiento <input type="number" name="mes_nacimiento" min="1" max="12" value="<?php echo esc_attr($value($persona, 'mes_nacimiento')); ?>"></label>
                <label>Anio nacimiento <input type="number" name="anio_nacimiento" value="<?php echo esc_attr($value($persona, 'anio_nacimiento')); ?>"></label>
                <label>Telefono <input type="text" name="telefono" value="<?php echo esc_attr($value($persona, 'telefono')); ?>"></label>
                <label>Codigo area <input type="text" name="codigo_area" value="<?php echo esc_attr($value($persona, 'codigo_area')); ?>"></label>
                <label>Email <input type="email" name="email" value="<?php echo esc_attr($value($persona, 'email')); ?>"></label>
                <label>Email ABC <input type="email" name="email_abc" value="<?php echo esc_attr($value($persona, 'email_abc')); ?>"></label>
                <label>Lugar nacimiento <input type="text" name="lugar_nacimiento" value="<?php echo esc_attr($value($persona, 'lugar_nacimiento')); ?>"></label>
                <label>Nacionalidad <input type="text" name="nacionalidad" value="<?php echo esc_attr($value($persona, 'nacionalidad')); ?>"></label>
                <label>Domicilio <input type="text" name="descripcion_domicilio" value="<?php echo esc_attr($value($persona, 'descripcion_domicilio')); ?>"></label>
                <label>Departamento <input type="text" name="departamento" value="<?php echo esc_attr($value($persona, 'departamento')); ?>"></label>
                <label>Localidad <input type="text" name="localidad" value="<?php echo esc_attr($value($persona, 'localidad')); ?>"></label>
                <label>Partido <input type="text" name="partido" value="<?php echo esc_attr($value($persona, 'partido')); ?>"></label>
            </div>

            <p><button type="submit" class="button button-primary">Guardar Datos Persona</button></p>
        </form>

        <h2>Alumno</h2>
        <?php if ($alumno === null) : ?>
            <p>No hay registro de alumno para esta persona. Al guardar estos datos se creara uno nuevo.</p>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="fines7-edit-form">
            <?php wp_nonce_field('fines7_save_alumno_' . $persona['id']); ?>
            <input type="hidden" name="action" value="fines7_save_alumno">
            <input type="hidden" name="persona_id" value="<?php echo esc_attr($persona['id']); ?>">
            <input type="hidden" name="alumno_id" value="<?php echo esc_attr($value($alumno, 'id')); ?>">

            <div class="fines7-form-grid">
                <label>
                    Estado de inscripcion
                    <input list="fines7-estados-inscripcion" name="estado_inscripcion" value="<?php echo esc_attr($value($alumno, 'estado_inscripcion')); ?>">
                    <datalist id="fines7-estados-inscripcion">
                        <?php foreach ($estadosInscripcion as $estadoInscripcion) : ?>
                            <option value="<?php echo esc_attr($estadoInscripcion); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>
                <label>
                    Plan
                    <select name="plan">
                        <option value="">Seleccione...</option>
                        <?php foreach ($planes as $plan) : ?>
                            <?php $planOptionLabel = trim(($plan['orientacion'] ?? '') . ' - ' . ($plan['resolucion'] ?? ''), ' -'); ?>
                            <option value="<?php echo esc_attr($plan['id']); ?>" <?php selected($value($alumno, 'plan'), $plan['id']); ?>>
                                <?php echo esc_html($planOptionLabel); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    Anio ingreso
                    <select name="anio_ingreso">
                        <option value="">Seleccione...</option>
                        <option value="1" <?php selected($value($alumno, 'anio_ingreso'), '1'); ?>>1</option>
                        <option value="2" <?php selected($value($alumno, 'anio_ingreso'), '2'); ?>>2</option>
                        <option value="3" <?php selected($value($alumno, 'anio_ingreso'), '3'); ?>>3</option>
                    </select>
                </label>
                <label>
                    Semestre ingreso
                    <select name="semestre_ingreso">
                        <option value="">Seleccione...</option>
                        <option value="1" <?php selected($value($alumno, 'semestre_ingreso'), '1'); ?>>1</option>
                        <option value="2" <?php selected($value($alumno, 'semestre_ingreso'), '2'); ?>>2</option>
                    </select>
                </label>
                <label>Anio inscripcion <input type="number" name="anio_inscripcion" value="<?php echo esc_attr($value($alumno, 'anio_inscripcion')); ?>"></label>
                <label>Semestre inscripcion <input type="number" name="semestre_inscripcion" value="<?php echo esc_attr($value($alumno, 'semestre_inscripcion')); ?>"></label>
                <label>Establecimiento <input type="text" name="establecimiento_inscripcion" value="<?php echo esc_attr($value($alumno, 'establecimiento_inscripcion')); ?>"></label>
                <label>Fecha titulacion <input type="date" name="fecha_titulacion" value="<?php echo esc_attr($value($alumno, 'fecha_titulacion')); ?>"></label>
                <label class="fines7-form-wide">Observaciones <textarea name="observaciones" rows="3"><?php echo esc_textarea($value($alumno, 'observaciones')); ?></textarea></label>
            </div>

            <fieldset class="fines7-check-grid">
                <legend>Legajo</legend>
                <label><input type="checkbox" name="tiene_dni" value="1" <?php checked((int) ($alumno['tiene_dni'] ?? 0), 1); ?>> Tiene DNI</label>
                <label><input type="checkbox" name="tiene_constancia" value="1" <?php checked((int) ($alumno['tiene_constancia'] ?? 0), 1); ?>> Tiene constancia</label>
                <label><input type="checkbox" name="tiene_certificado" value="1" <?php checked((int) ($alumno['tiene_certificado'] ?? 0), 1); ?>> Tiene certificado</label>
                <label><input type="checkbox" name="previas_completas" value="1" <?php checked((int) ($alumno['previas_completas'] ?? 0), 1); ?>> Previas completas</label>
                <label><input type="checkbox" name="tiene_partida" value="1" <?php checked((int) ($alumno['tiene_partida'] ?? 0), 1); ?>> Tiene partida</label>
                <label><input type="checkbox" name="confirmado_direccion" value="1" <?php checked((int) ($alumno['confirmado_direccion'] ?? 0), 1); ?>> Confirmado direccion</label>
            </fieldset>

            <p><button type="submit" class="button button-primary">Guardar Datos Alumno</button></p>
        </form>

        <?php if ($alumno !== null) : ?>
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
