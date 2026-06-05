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
$calificacionIndex = 0;
$renderCalificacionesTable = static function (array $calificaciones) use ($planLabel, $cursoSummary, &$calificacionIndex): void {
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
                        <div class="curso-picker" data-disposicion="<?= e($calificacion['disposicion'] ?? '') ?>">
                            <input class="form-control form-control-sm curso-search" value="<?= e($calificacion['curso'] ?? '') ?>" placeholder="ID curso o PFID" autocomplete="off">
                            <input class="curso-id" type="hidden" name="curso[<?= e($index) ?>]" value="<?= e($calificacion['curso'] ?? '') ?>">
                            <div class="small text-secondary curso-summary"><?= e($cursoSummary($calificacion)) ?></div>
                            <div class="curso-results" hidden></div>
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
                <label class="form-label">CUIL1 <input class="form-control" inputmode="numeric" maxlength="2" pattern="[0-9]{0,2}" name="cuil1" value="<?= e($v($persona, 'cuil1')) ?>"></label>
                <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($v($persona, 'numero_documento')) ?>" required></label>
                <label class="form-label">CUIL2 <input class="form-control" inputmode="numeric" maxlength="1" pattern="[0-9]{0,1}" name="cuil2" value="<?= e($v($persona, 'cuil2')) ?>"></label>
            </div>
            <div class="form-composite form-date-grid">
                <label class="form-label">Dia nac <input class="form-control" inputmode="numeric" maxlength="2" pattern="[0-9]{0,2}" name="dia_nacimiento" value="<?= e($v($persona, 'dia_nacimiento')) ?>"></label>
                <label class="form-label">Mes nac <input class="form-control" inputmode="numeric" maxlength="2" pattern="[0-9]{0,2}" name="mes_nacimiento" value="<?= e($v($persona, 'mes_nacimiento')) ?>"></label>
                <label class="form-label">Año nac <input class="form-control" inputmode="numeric" maxlength="4" pattern="[0-9]{0,4}" name="anio_nacimiento" value="<?= e($v($persona, 'anio_nacimiento')) ?>"></label>
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
            </div>
            <a class="btn btn-outline-primary" href="<?= e(url('/personas/' . $persona['id'] . '/alumno/constancias/alumno-regular/nueva')) ?>">Generar alumno regular</a>
        </div>

        <?php if (($constancias ?? []) === []) : ?>
            <div class="empty-state">No hay constancias emitidas para este alumno.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Titulo</th>
                        <th>Descripcion</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Validacion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($constancias as $constancia) : ?>
                        <tr>
                            <td><?= e($constancia['creado_en'] ?? '') ?></td>
                            <td><?= e($constancia['titulo'] ?? '') ?></td>
                            <td><?= e($constancia['descripcion'] ?? '') ?></td>
                            <td><?= e($constancia['archivo_nombre'] ?? '') ?></td>
                            <td><?= empty($constancia['anulado_en']) ? 'Activa' : 'Anulada' ?></td>
                            <td>
                                <?php if (empty($constancia['anulado_en'])) : ?>
                                    <a href="<?= e(constancias_url('/validar-constancia?id=' . rawurlencode((string) $constancia['id']) . '&clave=' . rawurlencode((string) $constancia['clave']))) ?>" target="_blank" rel="noopener">Abrir</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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
