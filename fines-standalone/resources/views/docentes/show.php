<?php
$v = static fn (?array $row, string $key): string => $row === null ? '' : (string) ($row[$key] ?? '');
$personaLabel = trim(implode(' ', array_filter([$v($persona, 'apellidos'), $v($persona, 'nombres')])));
$planLabel = static fn (array $row): string => trim(implode(' - ', array_filter([$row['plan_orientacion'] ?? '', $row['plan_resolucion'] ?? ''])));
$tramoAsignaturaLabel = static fn (array $row): string => trim(implode(' ', array_filter([
    $row['asignatura_label'] ?? '',
    $row['tramo_label'] ?? '',
])));
$cursoLabel = static function (array $row) use ($planLabel, $tramoAsignaturaLabel): string {
    return trim(implode(' | ', array_filter([
        $tramoAsignaturaLabel($row),
        $planLabel($row),
    ])));
};
$fecha = static function (mixed $value): string {
    if ($value === null || $value === '') {
        return '';
    }

    $date = \DateTimeImmutable::createFromFormat('Y-m-d', (string) $value);
    return $date === false ? (string) $value : $date->format('d/m/Y');
};
$cursoMetaLabel = static function (array $row) use ($fecha): string {
    $calendarioRango = trim(implode(' al ', array_filter([
        $fecha($row['calendario_inicio'] ?? ''),
        $fecha($row['calendario_fin'] ?? ''),
    ])));

    return trim(implode(' | ', array_filter([
        ($row['pfid'] ?? '') !== '' ? 'PFID ' . $row['pfid'] : '',
        $row['sede_label'] ?? '',
        $row['calendario_label'] ?? '',
        $calendarioRango,
    ])));
};
$numeroEntero = static fn (mixed $numero): string => $numero === null || $numero === '' ? '' : (string) ceil((float) $numero);
?>
<div class="page-header">
    <div>
        <h1><?= e($personaLabel ?: 'Docente') ?></h1>
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
    <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/docente')) ?>">
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
    <h2>Tomas</h2>
    <?php if ($tomas === []) : ?>
        <div class="empty-state">No hay tomas asignadas.</div>
    <?php else : ?>
        <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/docente/tomas')) ?>">
            <?= $csrf->field() ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Tipo mov.</th>
                        <th>Contralor</th>
                        <th>Planilla</th>
                        <th>Observaciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tomas as $index => $toma) : ?>
                        <tr>
                            <td>
                                <input type="hidden" name="toma_id[<?= e($index) ?>]" value="<?= e($toma['id']) ?>">
                                <input class="form-control form-control-sm" name="fecha_toma[<?= e($index) ?>]" value="<?= e($fecha($toma['fecha_toma'] ?? '')) ?>" placeholder="dd/mm/aaaa" inputmode="numeric">
                            </td>
                            <td>
                                <div class="fw-semibold"><?= e($cursoLabel($toma)) ?></div>
                                <div class="small text-secondary"><?= e($cursoMetaLabel($toma)) ?></div>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="estado[<?= e($index) ?>]">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($estadosToma as $estado) : ?>
                                        <option value="<?= e($estado) ?>" <?= selected($toma['estado'] ?? '', $estado) ?>><?= e($estado) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="tipo_movimiento[<?= e($index) ?>]">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($tiposMovimiento as $tipo) : ?>
                                        <option value="<?= e($tipo) ?>" <?= selected($toma['tipo_movimiento'] ?? '', $tipo) ?>><?= e($tipo) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="estado_contralor[<?= e($index) ?>]">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($estadosContralor as $estadoContralor) : ?>
                                        <option value="<?= e($estadoContralor) ?>" <?= selected($toma['estado_contralor'] ?? '', $estadoContralor) ?>><?= e($estadoContralor) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <div><?= e($toma['estado_planilla'] ?? '') ?></div>
                                <div class="small text-secondary"><?= e($toma['planilla_numero'] ?? '') ?></div>
                            </td>
                            <td><input class="form-control form-control-sm" name="observaciones_toma[<?= e($index) ?>]" value="<?= e($toma['observaciones'] ?? '') ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary" type="submit">Guardar tomas</button>
        </form>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Calificaciones completadas</h2>
    <?php if ($calificaciones === []) : ?>
        <div class="empty-state">No hay calificaciones completadas asociadas a las tomas del docente.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle app-table">
                <thead>
                <tr>
                    <th>Alumno</th>
                    <th>DNI</th>
                    <th>Asignatura</th>
                    <th>Tramo</th>
                    <th>Curso</th>
                    <th>N1</th>
                    <th>N2</th>
                    <th>N3</th>
                    <th>Nota final</th>
                    <th>CREC</th>
                    <th>Fecha</th>
                    <th>Observaciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($calificaciones as $calificacion) : ?>
                    <tr>
                        <td><?= e($calificacion['alumno_label'] ?? '') ?></td>
                        <td><?= e($calificacion['alumno_documento'] ?? '') ?></td>
                        <td><?= e($calificacion['asignatura_label'] ?? '') ?></td>
                        <td><?= e($calificacion['tramo_label'] ?? '') ?></td>
                        <td>
                            <div><?= e(($calificacion['pfid'] ?? '') !== '' ? 'PFID ' . $calificacion['pfid'] : '') ?></div>
                            <div class="small text-secondary"><?= e($calificacion['curso_id'] ?? '') ?></div>
                        </td>
                        <td><?= e($numeroEntero($calificacion['nota1'] ?? '')) ?></td>
                        <td><?= e($numeroEntero($calificacion['nota2'] ?? '')) ?></td>
                        <td><?= e($numeroEntero($calificacion['nota3'] ?? '')) ?></td>
                        <td><?= e($numeroEntero($calificacion['nota_final'] ?? '')) ?></td>
                        <td><?= e($numeroEntero($calificacion['crec'] ?? '')) ?></td>
                        <td><?= e($calificacion['fecha'] ?? '') ?></td>
                        <td><?= e($calificacion['observaciones'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

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
