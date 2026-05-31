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

    <?php if (!empty($formError)) : ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo esc_html($formError); ?></p>
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
    <?php elseif ($notice === 'comisiones_guardadas') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Comisiones guardadas.</p>
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
                <fieldset class="fines7-field-group fines7-field-group-cuil">
                    <label>CUIL1 <input type="number" name="cuil1" value="<?php echo esc_attr($value($persona, 'cuil1')); ?>"></label>
                    <label>DNI <input type="text" name="numero_documento" value="<?php echo esc_attr($value($persona, 'numero_documento')); ?>" required></label>
                    <label>CUIL2 <input type="number" name="cuil2" value="<?php echo esc_attr($value($persona, 'cuil2')); ?>"></label>
                </fieldset>
                <label>
                    Sexo
                    <select name="sexo">
                        <option value="">Seleccione...</option>
                        <option value="1" <?php selected($value($persona, 'sexo'), '1'); ?>>Masculino</option>
                        <option value="2" <?php selected($value($persona, 'sexo'), '2'); ?>>Femenino</option>
                        <option value="3" <?php selected($value($persona, 'sexo'), '3'); ?>>No binario</option>
                    </select>
                </label>
                <fieldset class="fines7-field-group fines7-field-group-date">
                    <label>Dia nac <input type="number" name="dia_nacimiento" min="1" max="31" value="<?php echo esc_attr($value($persona, 'dia_nacimiento')); ?>"></label>
                    <label>Mes nac <input type="number" name="mes_nacimiento" min="1" max="12" value="<?php echo esc_attr($value($persona, 'mes_nacimiento')); ?>"></label>
                    <label>Anio nac <input type="number" name="anio_nacimiento" value="<?php echo esc_attr($value($persona, 'anio_nacimiento')); ?>"></label>
                </fieldset>
                <fieldset class="fines7-field-group fines7-field-group-phone">
                    <label>Codigo area <input type="text" name="codigo_area" value="<?php echo esc_attr($value($persona, 'codigo_area')); ?>"></label>
                    <label>Telefono <input type="text" name="telefono" value="<?php echo esc_attr($value($persona, 'telefono')); ?>"></label>
                </fieldset>
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
                <fieldset class="fines7-field-group fines7-field-group-ingreso">
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
                </fieldset>
                <label>Fecha titulacion <input type="date" name="fecha_titulacion" value="<?php echo esc_attr($value($alumno, 'fecha_titulacion')); ?>"></label>
                <label class="fines7-inline-check"><input type="checkbox" name="confirmado_direccion" value="1" <?php checked((int) ($alumno['confirmado_direccion'] ?? 0), 1); ?>> Confirmado direccion</label>
                <label class="fines7-form-wide">Observaciones <textarea name="observaciones" rows="3"><?php echo esc_textarea($value($alumno, 'observaciones')); ?></textarea></label>
            </div>

            <p><button type="submit" class="button button-primary">Guardar Datos Alumno</button></p>
        </form>

        <?php if ($alumno !== null) : ?>
            <h2>Comisiones</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('fines7_save_alumno_comisiones_' . $persona['id']); ?>
                <input type="hidden" name="action" value="fines7_save_alumno_comisiones">
                <input type="hidden" name="persona_id" value="<?php echo esc_attr($persona['id']); ?>">
                <input type="hidden" name="alumno_id" value="<?php echo esc_attr($alumno['id']); ?>">

                <table class="wp-list-table widefat striped fines7-table">
                    <thead>
                        <tr>
                            <th>Comision</th>
                            <th>Periodo</th>
                            <th>Tramo</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comisiones as $index => $comision) : ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="alumno_comision_id[<?php echo esc_attr((string) $index); ?>]" value="<?php echo esc_attr($comision['id']); ?>">
                                    <div class="fines7-comision-picker fines7-comision-autocomplete">
                                        <input
                                            type="text"
                                            class="fines7-comision-search"
                                            value="<?php echo esc_attr($comision['pfid'] ?? ''); ?>"
                                            placeholder="Buscar PFID o ID"
                                            autocomplete="off"
                                        >
                                        <input
                                            type="hidden"
                                            class="fines7-comision-id"
                                            name="comision_ref[<?php echo esc_attr((string) $index); ?>]"
                                            value="<?php echo esc_attr($comision['comision_id']); ?>"
                                        >
                                        <span class="fines7-muted">
                                            <?php echo esc_html(trim('PFID ' . ($comision['pfid'] ?? '') . ' | ' . ($comision['sede_label'] ?? ''), ' |')); ?>
                                        </span>
                                        <div class="fines7-comision-results" hidden></div>
                                    </div>
                                </td>
                                <td><?php echo esc_html($comision['calendario_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($comision['tramo_label'] ?: ''); ?></td>
                                <td><?php echo esc_html($planLabel($comision)); ?></td>
                                <td>
                                    <select name="estado[<?php echo esc_attr((string) $index); ?>]">
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($estadosComision as $estadoComision) : ?>
                                            <option value="<?php echo esc_attr($estadoComision); ?>" <?php selected($comision['estado'], $estadoComision); ?>>
                                                <?php echo esc_html($estadoComision); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="checkbox" name="activo[<?php echo esc_attr((string) $index); ?>]" value="1" <?php checked((int) ($comision['activo'] ?? 0), 1); ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>
                                <div class="fines7-comision-picker fines7-comision-autocomplete">
                                    <input
                                        type="text"
                                        class="fines7-comision-search"
                                        value=""
                                        placeholder="Buscar PFID o ID"
                                        autocomplete="off"
                                    >
                                    <input type="hidden" class="fines7-comision-id" name="new_comision_ref" value="">
                                    <span class="fines7-muted">Nueva comision</span>
                                    <div class="fines7-comision-results" hidden></div>
                                </div>
                            </td>
                            <td colspan="3">Agregar nueva comision</td>
                            <td>
                                <select name="new_estado">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($estadosComision as $estadoComision) : ?>
                                        <option value="<?php echo esc_attr($estadoComision); ?>"><?php echo esc_html($estadoComision); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="new_activo" value="1">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p><button type="submit" class="button button-primary">Guardar Comisiones</button></p>
            </form>

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
