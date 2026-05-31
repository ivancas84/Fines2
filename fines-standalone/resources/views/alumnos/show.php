<?php
$v = static fn (?array $row, string $key): string => $row === null ? '' : (string) ($row[$key] ?? '');
$planLabel = static fn (?array $row): string => $row === null ? '' : trim(implode(' - ', array_filter([$row['plan_orientacion'] ?? '', $row['plan_resolucion'] ?? ''])));
$personaLabel = trim(implode(' ', array_filter([$v($persona, 'apellidos'), $v($persona, 'nombres')])));
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
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($v($persona, 'nombres')) ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($v($persona, 'apellidos')) ?>"></label>
            <label class="form-label">CUIL1 <input class="form-control" type="number" name="cuil1" value="<?= e($v($persona, 'cuil1')) ?>"></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($v($persona, 'numero_documento')) ?>" required></label>
            <label class="form-label">CUIL2 <input class="form-control" type="number" name="cuil2" value="<?= e($v($persona, 'cuil2')) ?>"></label>
            <label class="form-label">
                Sexo
                <select class="form-select" name="sexo">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= selected($v($persona, 'sexo'), '1') ?>>Masculino</option>
                    <option value="2" <?= selected($v($persona, 'sexo'), '2') ?>>Femenino</option>
                    <option value="3" <?= selected($v($persona, 'sexo'), '3') ?>>No binario</option>
                </select>
            </label>
            <label class="form-label">Dia nac <input class="form-control" type="number" min="1" max="31" name="dia_nacimiento" value="<?= e($v($persona, 'dia_nacimiento')) ?>"></label>
            <label class="form-label">Mes nac <input class="form-control" type="number" min="1" max="12" name="mes_nacimiento" value="<?= e($v($persona, 'mes_nacimiento')) ?>"></label>
            <label class="form-label">Anio nac <input class="form-control" type="number" name="anio_nacimiento" value="<?= e($v($persona, 'anio_nacimiento')) ?>"></label>
            <label class="form-label">Codigo area <input class="form-control" name="codigo_area" value="<?= e($v($persona, 'codigo_area')) ?>"></label>
            <label class="form-label">Telefono <input class="form-control" name="telefono" value="<?= e($v($persona, 'telefono')) ?>"></label>
            <label class="form-label">Email <input class="form-control" type="email" name="email" value="<?= e($v($persona, 'email')) ?>"></label>
            <label class="form-label">Email ABC <input class="form-control" type="email" name="email_abc" value="<?= e($v($persona, 'email_abc')) ?>"></label>
            <label class="form-label">Lugar nacimiento <input class="form-control" name="lugar_nacimiento" value="<?= e($v($persona, 'lugar_nacimiento')) ?>"></label>
            <label class="form-label">Nacionalidad <input class="form-control" name="nacionalidad" value="<?= e($v($persona, 'nacionalidad')) ?>"></label>
            <label class="form-label form-wide">Domicilio <input class="form-control" name="descripcion_domicilio" value="<?= e($v($persona, 'descripcion_domicilio')) ?>"></label>
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
            <label class="form-label">
                Anio ingreso
                <select class="form-select" name="anio_ingreso">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= selected($v($alumno, 'anio_ingreso'), '1') ?>>1</option>
                    <option value="2" <?= selected($v($alumno, 'anio_ingreso'), '2') ?>>2</option>
                    <option value="3" <?= selected($v($alumno, 'anio_ingreso'), '3') ?>>3</option>
                </select>
            </label>
            <label class="form-label">
                Semestre ingreso
                <select class="form-select" name="semestre_ingreso">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= selected($v($alumno, 'semestre_ingreso'), '1') ?>>1</option>
                    <option value="2" <?= selected($v($alumno, 'semestre_ingreso'), '2') ?>>2</option>
                </select>
            </label>
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
        <h2>Comisiones</h2>
        <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno/comisiones')) ?>">
            <?= $csrf->field() ?>
            <input type="hidden" name="alumno_id" value="<?= e($alumno['id']) ?>">
            <div class="table-responsive">
                <table class="table align-middle app-table">
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
                                <input type="hidden" name="alumno_comision_id[<?= e($index) ?>]" value="<?= e($comision['id']) ?>">
                                <div class="comision-picker">
                                    <input class="form-control comision-search" value="<?= e($comision['pfid'] ?? '') ?>" placeholder="PFID o ID" autocomplete="off">
                                    <input class="comision-id" type="hidden" name="comision_ref[<?= e($index) ?>]" value="<?= e($comision['comision_id']) ?>">
                                    <div class="small text-secondary comision-summary"><?= e(trim('PFID ' . ($comision['pfid'] ?? '') . ' | ' . ($comision['sede_label'] ?? ''), ' |')) ?></div>
                                    <div class="comision-results" hidden></div>
                                </div>
                            </td>
                            <td><?= e($comision['calendario_label'] ?? '') ?></td>
                            <td><?= e($comision['tramo_label'] ?? '') ?></td>
                            <td><?= e($planLabel($comision)) ?></td>
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
                        <td colspan="3">Agregar nueva comision</td>
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
        <h2>Calificaciones</h2>
        <?php if ($calificaciones === []) : ?>
            <div class="empty-state">No se encontraron calificaciones para este alumno.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                    <tr>
                        <th>Asignatura</th>
                        <th>Tramo</th>
                        <th>Plan</th>
                        <th>Nota final</th>
                        <th>CREC</th>
                        <th>PFID</th>
                        <th>Periodo</th>
                        <th>Docente</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($calificaciones as $calificacion) : ?>
                        <tr>
                            <td><?= e($calificacion['asignatura_label'] ?? '') ?></td>
                            <td><?= e($calificacion['tramo_label'] ?? '') ?></td>
                            <td><?= e($planLabel($calificacion)) ?></td>
                            <td><?= e($calificacion['nota_final'] ?? '') ?></td>
                            <td><?= e($calificacion['crec'] ?? '') ?></td>
                            <td><?= e($calificacion['pfid'] ?? '') ?></td>
                            <td><?= e($calificacion['calendario_label'] ?? '') ?></td>
                            <td><?= e($calificacion['docente_label'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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
