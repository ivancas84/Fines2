<?php
$v = static fn (?array $row, string $key): string => $row === null ? '' : (string) ($row[$key] ?? '');
$planLabel = static fn (?array $row): string => $row === null ? '' : trim(implode(' - ', array_filter([$row['plan_orientacion'] ?? '', $row['plan_resolucion'] ?? ''])));
$comisionSummary = static fn (array $row): string => trim(implode(' | ', array_filter([
    'PFID ' . ($row['pfid'] ?? ''),
    $row['sede_label'] ?? '',
    $row['calendario_label'] ?? '',
    $row['tramo_label'] ?? '',
    $planLabel($row),
])));
$cursoSummary = static function (array $row): string {
    if (($row['curso'] ?? '') === '') {
        return 'Sin curso seleccionado';
    }

    return trim(implode(' | ', array_filter([
        ($row['pfid'] ?? '') !== '' ? 'PFID ' . $row['pfid'] : '',
        $row['calendario_label'] ?? '',
        $row['docente_label'] ?? '',
        trim(implode(' ', array_filter([
            $row['asignatura_label'] ?? '',
            $row['tramo_label'] ?? '',
        ]))),
    ]))) ?: 'Sin curso seleccionado';
};
$personaLabel = trim(implode(' ', array_filter([$v($persona, 'apellidos'), $v($persona, 'nombres')])));
$anioEnLetras = static fn (mixed $value): string => match ((string) $value) {
    '1' => 'primer',
    '2' => 'segundo',
    '3' => 'tercer',
    default => (string) $value,
};
$anioCursado = static fn (mixed $value): ?string => match ((string) $value) {
    '1' => 'Primero',
    '2' => 'Segundo',
    '3' => 'Tercero',
    default => null,
};
$notaAprobada = static function (array $calificacion): string {
    if ((float) ($calificacion['nota_final'] ?? 0) >= 7) {
        return (string) round((float) $calificacion['nota_final']);
    }

    if ((float) ($calificacion['crec'] ?? 0) >= 4) {
        return (string) round((float) $calificacion['crec']) . 'c';
    }

    return '';
};
$calificacionAprobada = static fn (array $calificacion): bool => $notaAprobada($calificacion) !== '';
$materiasTable = static function (array $calificaciones, bool $incluirNota) use ($notaAprobada): string {
    if ($calificaciones === []) {
        return '';
    }

    $html = '<table><thead><tr><th>Asignatura</th><th>Tramo</th>';
    if ($incluirNota) {
        $html .= '<th>Nota</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($calificaciones as $calificacion) {
        $html .= '<tr><td>' . e($calificacion['asignatura_label'] ?? '') . '</td>';
        $html .= '<td>' . e($calificacion['tramo_label'] ?? '') . '</td>';
        if ($incluirNota) {
            $html .= '<td>' . e($notaAprobada($calificacion)) . '</td>';
        }
        $html .= '</tr>';
    }

    return $html . '</tbody></table>';
};
$constanciaUrl = static function (string $path, array $params): string {
    $params = array_filter($params, static fn (mixed $value): bool => $value !== null && $value !== '');

    return constancias_url($path . ($params === [] ? '' : '?' . http_build_query($params)));
};
$calificacionesAprobadasPase = array_values(array_filter(
    array_merge($calificacionesPlan ?? [], $calificacionesOtroPlan ?? []),
    $calificacionAprobada,
));
$calificacionesPendientesPase = array_values(array_filter(
    $calificacionesPlan ?? [],
    static fn (array $calificacion): bool => !$calificacionAprobada($calificacion),
));
$aniosCursadosPase = array_values(array_unique(array_filter(array_map(
    static function (array $calificacion) use ($anioCursado): ?string {
        $tramo = (string) ($calificacion['tramo_label'] ?? '');
        return $anioCursado($tramo === '' ? '' : strtok($tramo, '-'));
    },
    $calificacionesAprobadasPase,
))));
$academicParams = [
    'nombres' => $v($persona, 'nombres'),
    'apellidos' => $v($persona, 'apellidos'),
    'numero_documento' => $v($persona, 'numero_documento'),
    'anio' => $anioEnLetras($ultimaComision['planificacion_anio'] ?? ($alumno['anio_ingreso'] ?? '')),
    'modalidad' => 'Programa Fines 2 Trayecto Secundario',
    'orientacion' => (string) ($ultimaComision['plan_orientacion'] ?? ($alumno['plan_orientacion'] ?? 'Ciencias Sociales')),
    'resolucion' => (string) ($ultimaComision['plan_resolucion'] ?? ($alumno['plan_resolucion'] ?? '2993/22')),
    'presentado' => 'Quien Corresponda',
    'incluir_firmas' => '1',
];
$paseParams = array_merge($academicParams, [
    'anio' => $aniosCursadosPase === [] ? ($academicParams['anio'] ?? '') : implode(', ', $aniosCursadosPase),
    'materias_aprobadas_html' => $materiasTable($calificacionesAprobadasPase, true),
    'materias_desaprobadas_html' => $materiasTable($calificacionesPendientesPase, false),
]);
$basicConstanciaParams = [
    'nombres' => $v($persona, 'nombres'),
    'apellidos' => $v($persona, 'apellidos'),
    'numero_documento' => $v($persona, 'numero_documento'),
    'presentado' => 'Quien Corresponda',
    'incluir_firmas' => '1',
];
$calificacionIndex = 0;
$renderCalificacionesTable = static function (array $calificaciones) use ($alumno, $planLabel, $cursoSummary, &$calificacionIndex): void {
    $isAprobada = static fn (array $calificacion): bool => (float) ($calificacion['nota_final'] ?? 0) >= 7
        || (float) ($calificacion['crec'] ?? 0) >= 4;
    $numeroEntero = static fn (mixed $numero): string => $numero === null || $numero === '' ? '' : (string) ceil((float) $numero);
    ?>
    <div class="table-responsive">
        <table class="table table-hover app-table">
            <thead>
            <tr>
                <th>Asignatura</th>
                <th>Tramo</th>
                <th>Plan</th>
                <th>Nota final</th>
                <th>CREC</th>
                <th>Curso</th>
                <th>Observaciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($calificaciones as $calificacion) : ?>
                <?php $index = $calificacionIndex++; ?>
                <tr class="<?= $isAprobada($calificacion) ? 'calificacion-aprobada' : 'calificacion-desaprobada' ?>">
                    <td><?= e($calificacion['asignatura_label'] ?? '') ?></td>
                    <td><?= e($calificacion['tramo_label'] ?? '') ?></td>
                    <td><?= e($planLabel($calificacion)) ?></td>
                    <td>
                        <input type="hidden" name="calificacion_id[<?= e($index) ?>]" value="<?= e($calificacion['id'] ?? '') ?>">
                        <input class="form-control form-control-sm calificacion-number" type="number" step="1" name="nota_final[<?= e($index) ?>]" value="<?= e($numeroEntero($calificacion['nota_final'] ?? '')) ?>">
                    </td>
                    <td><input class="form-control form-control-sm calificacion-number" type="number" step="1" name="crec[<?= e($index) ?>]" value="<?= e($numeroEntero($calificacion['crec'] ?? '')) ?>"></td>
                    <td>
                        <div class="curso-picker" data-alumno="<?= e($alumno['id'] ?? '') ?>" data-disposicion="<?= e($calificacion['disposicion'] ?? '') ?>">
                            <button class="btn btn-sm btn-outline-primary curso-associate" type="button">Asociar curso</button>
                            <input class="curso-id" type="hidden" name="curso[<?= e($index) ?>]" value="<?= e($calificacion['curso'] ?? '') ?>">
                            <div class="small text-secondary curso-summary"><?= e($cursoSummary($calificacion)) ?></div>
                        </div>
                    </td>
                    <td><input class="form-control form-control-sm" name="observaciones_calificacion[<?= e($index) ?>]" value="<?= e($calificacion['observaciones'] ?? '') ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
};
?>
<div class="page-header">
    <div>
        <h1><?= e($personaLabel ?: 'Alumno') ?></h1>
        <p class="text-secondary mb-0">DNI <?= e($v($persona, 'numero_documento')) ?></p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/personas')) ?>">Volver</a>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<?php if (!$programaFines['connected']) : ?>
    <div class="alert alert-info">
        <a href="<?= e(url('/programafines')) ?>">Conectá ProgramaFines</a> para comparar los datos de este alumno.
    </div>
<?php else : ?>
    <section class="panel">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2>ProgramaFines</h2>
                <?php if (!empty($programaFines['error'])) : ?>
                    <p class="text-danger mb-0"><?= e($programaFines['error']) ?></p>
                <?php elseif (!$programaFines['exists']) : ?>
                    <p class="text-secondary mb-0">El alumno no existe en ProgramaFines.</p>
                <?php elseif ($programaFines['differences'] === []) : ?>
                    <p class="text-success mb-0">El alumno existe y no se encontraron diferencias en los datos comparados.</p>
                <?php else : ?>
                    <p class="text-warning mb-0">Se encontraron <?= e((string) count($programaFines['differences'])) ?> diferencias.</p>
                <?php endif; ?>
            </div>
            <?php if ($auth->canEdit() && $programaFines['exists'] && empty($programaFines['error'])) : ?>
                <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/programafines/actualizar')) ?>" onsubmit="return confirm('¿Reemplazar los datos del alumno en ProgramaFines con los datos locales?')">
                    <?= $csrf->field() ?>
                    <button class="btn btn-outline-success" type="submit">Actualizar ProgramaFines</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($programaFines['differences'] !== []) : ?>
            <div class="table-responsive mt-3">
                <table class="table table-sm app-table">
                    <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Base local</th>
                        <th>ProgramaFines</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($programaFines['differences'] as $difference) : ?>
                        <tr>
                            <td><strong><?= e($difference['label']) ?></strong></td>
                            <td><?= e($difference['local']) ?></td>
                            <td><?= e($difference['remote']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<section class="panel">
    <h2>Persona</h2>
    <form method="post" action="<?= e(url('/personas/' . $persona['id'])) ?>">
        <?= $csrf->field() ?>
        <div class="form-grid form-grid-persona">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($v($persona, 'nombres')) ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($v($persona, 'apellidos')) ?>"></label>
            <label class="form-label">
                Sexo
                <select class="form-select" name="sexo">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= selected($v($persona, 'sexo'), '1') ?>>Masculino</option>
                    <option value="2" <?= selected($v($persona, 'sexo'), '2') ?>>Femenino</option>
                    <option value="3" <?= selected($v($persona, 'sexo'), '3') ?>>No binario</option>
                </select>
            </label>
            <div class="form-composite form-cuil-grid">
                <label class="form-label">CUIL1 <input class="form-control" inputmode="numeric" minlength="2" maxlength="2" pattern="[0-9]{2}" name="cuil1" value="<?= e($v($persona, 'cuil1')) ?>"></label>
                <label class="form-label">DNI <input class="form-control" inputmode="numeric" minlength="8" maxlength="8" pattern="[0-9]{8}" name="numero_documento" value="<?= e($v($persona, 'numero_documento')) ?>" required></label>
                <label class="form-label">CUIL2 <input class="form-control" inputmode="numeric" minlength="1" maxlength="1" pattern="[0-9]" name="cuil2" value="<?= e($v($persona, 'cuil2')) ?>"></label>
            </div>
            <div class="form-composite form-date-grid">
                <label class="form-label">Día nac. <input class="form-control" type="number" min="1" max="31" name="dia_nacimiento" value="<?= e($v($persona, 'dia_nacimiento')) ?>"></label>
                <label class="form-label">Mes nac. <input class="form-control" type="number" min="1" max="12" name="mes_nacimiento" value="<?= e($v($persona, 'mes_nacimiento')) ?>"></label>
                <label class="form-label">Año nac. <input class="form-control" type="number" min="1900" max="<?= e(date('Y')) ?>" name="anio_nacimiento" value="<?= e($v($persona, 'anio_nacimiento')) ?>"></label>
            </div>
            <div class="form-composite form-phone-grid">
                <label class="form-label">Codigo area <input class="form-control" name="codigo_area" value="<?= e($v($persona, 'codigo_area')) ?>"></label>
                <label class="form-label">Telefono <input class="form-control" name="telefono" value="<?= e($v($persona, 'telefono')) ?>"></label>
            </div>
            <label class="form-label">Email <input class="form-control" type="email" name="email" value="<?= e($v($persona, 'email')) ?>"></label>
            <label class="form-label">Email ABC <input class="form-control" type="email" name="email_abc" value="<?= e($v($persona, 'email_abc')) ?>"></label>
            <label class="form-label">Lugar nacimiento <input class="form-control" name="lugar_nacimiento" value="<?= e($v($persona, 'lugar_nacimiento')) ?>"></label>
            <label class="form-label">Nacionalidad <input class="form-control" name="nacionalidad" value="<?= e($v($persona, 'nacionalidad')) ?>"></label>
            <label class="form-label">Domicilio <input class="form-control" name="descripcion_domicilio" value="<?= e($v($persona, 'descripcion_domicilio')) ?>"></label>
            <label class="form-label">Departamento <input class="form-control" name="departamento" value="<?= e($v($persona, 'departamento')) ?>"></label>
            <label class="form-label">Localidad <input class="form-control" name="localidad" value="<?= e($v($persona, 'localidad')) ?>"></label>
            <label class="form-label">Partido <input class="form-control" name="partido" value="<?= e($v($persona, 'partido')) ?>"></label>
        </div>
        <button class="btn btn-primary mt-3" type="submit">Guardar persona</button>
    </form>
</section>

<section class="panel">
    <h2>Alumno</h2>
    <?php if ($alumno === null) : ?>
        <div class="alert alert-info">No hay registro de alumno para esta persona. Al guardar se creara uno nuevo.</div>
    <?php endif; ?>
    <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno')) ?>">
        <?= $csrf->field() ?>
        <input type="hidden" name="alumno_id" value="<?= e($v($alumno, 'id')) ?>">
        <div class="form-grid">
            <label class="form-label">
                Plan
                <select class="form-select" name="plan">
                    <option value="">Seleccione...</option>
                    <?php foreach ($planes as $plan) : ?>
                        <?php $label = trim(($plan['orientacion'] ?? '') . ' - ' . ($plan['resolucion'] ?? ''), ' -'); ?>
                        <option value="<?= e($plan['id']) ?>" <?= selected($v($alumno, 'plan'), $plan['id']) ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="form-composite form-ingreso-grid">
                <label class="form-label">
                    Año ing
                    <select class="form-select" name="anio_ingreso">
                        <option value="">Seleccione...</option>
                        <option value="1" <?= selected($v($alumno, 'anio_ingreso'), '1') ?>>1</option>
                        <option value="2" <?= selected($v($alumno, 'anio_ingreso'), '2') ?>>2</option>
                        <option value="3" <?= selected($v($alumno, 'anio_ingreso'), '3') ?>>3</option>
                    </select>
                </label>
                <label class="form-label">
                    Sem ing
                    <select class="form-select" name="semestre_ingreso">
                        <option value="">Seleccione...</option>
                        <option value="1" <?= selected($v($alumno, 'semestre_ingreso'), '1') ?>>1</option>
                        <option value="2" <?= selected($v($alumno, 'semestre_ingreso'), '2') ?>>2</option>
                    </select>
                </label>
            </div>
            <label class="form-label">Fecha titulacion <input class="form-control" type="date" name="fecha_titulacion" value="<?= e($v($alumno, 'fecha_titulacion')) ?>"></label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="confirmado_direccion" value="1" <?= checked($alumno['confirmado_direccion'] ?? 0) ?>>
                <span class="form-check-label">Confirmado direccion</span>
            </label>
            <label class="form-label form-wide">Observaciones <textarea class="form-control" name="observaciones" rows="3"><?= e($v($alumno, 'observaciones')) ?></textarea></label>
        </div>
        <button class="btn btn-primary mt-3" type="submit">Guardar alumno</button>
    </form>
</section>

<?php if ($alumno !== null) : ?>
    <section class="panel">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2>Constancias</h2>
                <p class="text-secondary mb-0">Se abren en abcconstancias.com.ar con los datos precargados.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" target="_blank" rel="noopener" href="<?= e($constanciaUrl('/constancias/alumno-regular/nueva', $academicParams)) ?>">Alumno regular</a>
                <a class="btn btn-outline-primary" target="_blank" rel="noopener" href="<?= e($constanciaUrl('/constancias/titulo-tramite/nueva', $academicParams)) ?>">Titulo en tramite</a>
                <a class="btn btn-outline-primary" target="_blank" rel="noopener" href="<?= e($constanciaUrl('/constancias/vacante/nueva', $basicConstanciaParams)) ?>">Vacante</a>
                <a class="btn btn-outline-primary" target="_blank" rel="noopener" href="<?= e($constanciaUrl('/constancias/pase/nueva', $paseParams)) ?>">Pase</a>
            </div>
        </div>
    </section>

    <section class="panel">
        <h2>Comisiones</h2>
        <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno/comisiones')) ?>">
            <?= $csrf->field() ?>
            <input type="hidden" name="alumno_id" value="<?= e($alumno['id']) ?>">
            <div class="table-responsive">
                <table class="table align-middle app-table">
                    <thead>
                    <tr>
                        <th>Comision</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comisiones as $index => $comision) : ?>
                        <tr>
                            <td>
                                <input type="hidden" name="alumno_comision_id[<?= e($index) ?>]" value="<?= e($comision['id']) ?>">
                                <div class="comision-picker">
                                    <input class="form-control comision-search" value="<?= e($comision['pfid'] ?? '') ?>" placeholder="PFID o ID" autocomplete="off">
                                    <input class="comision-id" type="hidden" name="comision_ref[<?= e($index) ?>]" value="<?= e($comision['comision_id']) ?>">
                                    <div class="small text-secondary comision-summary"><?= e($comisionSummary($comision)) ?></div>
                                    <div class="comision-results" hidden></div>
                                </div>
                            </td>
                            <td>
                                <select class="form-select" name="estado[<?= e($index) ?>]">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($estadosComision as $estado) : ?>
                                        <option value="<?= e($estado) ?>" <?= selected($comision['estado'] ?? '', $estado) ?>><?= e($estado) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input class="form-check-input" type="checkbox" name="activo[<?= e($index) ?>]" value="1" <?= checked($comision['activo'] ?? 0) ?>></td>
                            <td>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    type="submit"
                                    name="alumno_comision_delete_id"
                                    value="<?= e($comision['id']) ?>"
                                    formaction="<?= e(url('/personas/' . $persona['id'] . '/alumno/comisiones/eliminar')) ?>"
                                    formmethod="post"
                                    onclick="return confirm('Eliminar esta comision del alumno?')"
                                >Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>
                            <div class="comision-picker">
                                <input class="form-control comision-search" value="" placeholder="PFID o ID" autocomplete="off">
                                <input class="comision-id" type="hidden" name="new_comision_ref" value="">
                                <div class="small text-secondary comision-summary">Nueva comision</div>
                                <div class="comision-results" hidden></div>
                            </div>
                        </td>
                        <td>
                            <select class="form-select" name="new_estado">
                                <option value="">Seleccione...</option>
                                <?php foreach ($estadosComision as $estado) : ?>
                                    <option value="<?= e($estado) ?>"><?= e($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input class="form-check-input" type="checkbox" name="new_activo" value="1"></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary" type="submit">Guardar comisiones</button>
        </form>
    </section>

    <section class="panel">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2>Calificaciones</h2>
            </div>
            <?php if (!empty($alumno['plan'])) : ?>
                <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno/calificaciones/sincronizar')) ?>">
                    <?= $csrf->field() ?>
                    <button class="btn btn-outline-primary" type="submit">Sincronizar Calificaciones</button>
                </form>
            <?php endif; ?>
        </div>

        <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno/calificaciones')) ?>">
            <?= $csrf->field() ?>
            <input type="hidden" name="alumno_id" value="<?= e($alumno['id']) ?>">

            <h3 class="h5 mt-4">Calificaciones del plan</h3>
            <?php if ($calificacionesPlan === []) : ?>
                <div class="empty-state">No se encontraron calificaciones para este alumno.</div>
            <?php else : ?>
                <?php $renderCalificacionesTable($calificacionesPlan); ?>
            <?php endif; ?>

            <h3 class="h5 mt-4">Calificaciones aprobadas de otro plan</h3>
            <?php if ($calificacionesOtroPlan === []) : ?>
                <div class="empty-state">No se encontraron calificaciones adicionales para este alumno.</div>
            <?php else : ?>
                <?php $renderCalificacionesTable($calificacionesOtroPlan); ?>
            <?php endif; ?>

            <button class="btn btn-primary" type="submit">Guardar calificaciones</button>
        </form>
    </section>
<?php endif; ?>

<section class="panel">
    <h2>Detalle</h2>
    <?php if ($detalles === []) : ?>
        <div class="empty-state">No hay detalles.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover app-table">
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
                    <tr>
                        <td><?= e($detalle['fecha'] ?? '') ?></td>
                        <td><?= e($detalle['tipo'] ?? '') ?></td>
                        <td><?= e($detalle['asunto'] ?? '') ?></td>
                        <td>
                            <?php if (($detalle['file_content'] ?? '') !== '') : ?>
                                <a href="<?= e('https://planfines2.com.ar/upload/' . ltrim((string) $detalle['file_content'], '/')) ?>"><?= e($detalle['descripcion'] ?? '') ?></a>
                            <?php else : ?>
                                <?= e($detalle['descripcion'] ?? '') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
