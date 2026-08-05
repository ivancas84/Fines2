<?php
$isNew = !empty($isNew);
$canEdit = $auth->canEdit();
$sedeId = (string) ($sede['id'] ?? '');
$formAction = $isNew
    ? url('/sedes')
    : url('/sedes/' . rawurlencode($sedeId));

$formatDateInput = static function (mixed $value): string {
    $raw = trim((string) ($value ?? ''));
    if ($raw === '') {
        return '';
    }
    $date = date_create($raw);
    return $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $raw;
};

$formatFecha = static function (mixed $value): string {
    $raw = trim((string) ($value ?? ''));
    if ($raw === '') {
        return '—';
    }
    $date = date_create($raw);
    return $date instanceof DateTimeInterface ? $date->format('d/m/Y') : $raw;
};

$sedeLabel = trim(implode(' - ', array_filter([
    (string) ($sede['numero'] ?? ''),
    (string) ($sede['nombre'] ?? ''),
])));
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/sedes')) ?>">&larr; Volver a sedes</a>
        <h1 class="mt-2"><?= $isNew ? 'Nueva sede' : 'Editar sede' ?></h1>
        <p class="text-secondary mb-0">
            <?= e($isNew
                ? 'Completá los datos de la sede. Las designaciones se cargan después de crearla.'
                : ($sedeLabel !== '' ? $sedeLabel : 'Sede')) ?>
        </p>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if (!$canEdit) : ?>
    <div class="alert alert-warning">Solo lectura: no tenés permisos para modificar sedes.</div>
<?php endif; ?>

<section class="panel">
    <h2>Datos de la sede</h2>
    <form method="post" action="<?= e($formAction) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="numero">Número <span class="text-danger">*</span></label>
                <input class="form-control"
                       type="text"
                       name="numero"
                       id="numero"
                       maxlength="45"
                       value="<?= e((string) ($sede['numero'] ?? '')) ?>"
                       <?= $canEdit ? 'required' : 'disabled' ?>>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                <input class="form-control"
                       type="text"
                       name="nombre"
                       id="nombre"
                       maxlength="255"
                       value="<?= e((string) ($sede['nombre'] ?? '')) ?>"
                       <?= $canEdit ? 'required' : 'disabled' ?>>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="fecha_traspaso">Fecha de traspaso</label>
                <input class="form-control"
                       type="date"
                       name="fecha_traspaso"
                       id="fecha_traspaso"
                       value="<?= e($formatDateInput($sede['fecha_traspaso'] ?? null)) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="tipo_sede">Tipo de sede</label>
                <select class="form-select" name="tipo_sede" id="tipo_sede" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">-- Sin tipo --</option>
                    <?php foreach ($tiposSede as $tipo) : ?>
                        <option value="<?= e($tipo['id']) ?>" <?= selected($sede['tipo_sede_id'] ?? '', $tipo['id']) ?>>
                            <?= e($tipo['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="centro_educativo">Centro educativo (CENS)</label>
                <select class="form-select" name="centro_educativo" id="centro_educativo" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">-- Sin centro --</option>
                    <?php foreach ($centrosEducativos as $centro) : ?>
                        <option value="<?= e($centro['id']) ?>" <?= selected($sede['centro_educativo_id'] ?? '', $centro['id']) ?>>
                            <?= e($centro['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="pfid">PFID</label>
                <input class="form-control"
                       type="text"
                       name="pfid"
                       id="pfid"
                       maxlength="45"
                       value="<?= e((string) ($sede['pfid'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-12">
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea class="form-control"
                          name="observaciones"
                          id="observaciones"
                          rows="3"
                          <?= $canEdit ? '' : 'disabled' ?>><?= e((string) ($sede['observaciones'] ?? '')) ?></textarea>
            </div>
        </div>

        <h3 class="h5 mt-4">Domicilio</h3>
        <p class="text-secondary small">Opcional. Si completás alguno, calle, número y localidad son obligatorios.</p>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label" for="domicilio_calle">Calle</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_calle"
                       id="domicilio_calle"
                       maxlength="45"
                       value="<?= e((string) ($sede['domicilio_calle'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="domicilio_numero">Número</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_numero"
                       id="domicilio_numero"
                       maxlength="45"
                       value="<?= e((string) ($sede['domicilio_numero'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="domicilio_entre">Entre</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_entre"
                       id="domicilio_entre"
                       maxlength="45"
                       value="<?= e((string) ($sede['domicilio_entre'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="domicilio_piso">Piso</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_piso"
                       id="domicilio_piso"
                       maxlength="45"
                       value="<?= e((string) ($sede['domicilio_piso'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="domicilio_departamento">Depto</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_departamento"
                       id="domicilio_departamento"
                       maxlength="45"
                       value="<?= e((string) ($sede['domicilio_departamento'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="domicilio_barrio">Barrio</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_barrio"
                       id="domicilio_barrio"
                       maxlength="255"
                       value="<?= e((string) ($sede['domicilio_barrio'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="domicilio_localidad">Localidad</label>
                <input class="form-control"
                       type="text"
                       name="domicilio_localidad"
                       id="domicilio_localidad"
                       maxlength="255"
                       value="<?= e((string) ($sede['domicilio_localidad'] ?? '')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
        </div>

        <?php if ($canEdit) : ?>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <button class="btn btn-primary" type="submit">
                    <?= $isNew ? 'Crear sede' : 'Guardar sede' ?>
                </button>
                <a class="btn btn-outline-secondary" href="<?= e(url('/sedes')) ?>">Cancelar</a>
            </div>
        <?php else : ?>
            <div class="mt-4">
                <a class="btn btn-outline-secondary" href="<?= e(url('/sedes')) ?>">Volver</a>
            </div>
        <?php endif; ?>
    </form>
</section>

<?php if ($isNew) : ?>
    <section class="panel mt-3">
        <h2>Designaciones</h2>
        <p class="text-secondary mb-0">
            Después de crear la sede vas a poder agregar designaciones por DNI.
        </p>
    </section>
<?php else : ?>
    <section class="panel mt-3">
        <h2>Designaciones</h2>
        <p class="text-secondary">
            Para agregar una designación se pide el DNI de una persona ya cargada en el sistema.
            Si no existe, primero hay que crearla en <a href="<?= e(url('/personas')) ?>">Personas</a>.
        </p>

        <?php if ($designaciones === []) : ?>
            <div class="empty-state mb-3">No hay designaciones en esta sede.</div>
        <?php else : ?>
            <form method="post"
                  action="<?= e(url('/sedes/' . rawurlencode($sedeId) . '/designaciones')) ?>"
                  id="form-designaciones">
                <?= $csrf->field() ?>
                <input type="hidden" name="delete_designacion_id" id="delete_designacion_id" value="">
                <div class="table-responsive">
                    <table class="table table-hover align-middle app-table">
                        <thead>
                        <tr>
                            <th>Persona</th>
                            <th>DNI</th>
                            <th>Cargo</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <?php if ($canEdit) : ?><th></th><?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($designaciones as $index => $designacion) : ?>
                            <?php
                            $personaNombre = trim(implode(' ', array_filter([
                                (string) ($designacion['persona_apellidos'] ?? ''),
                                (string) ($designacion['persona_nombres'] ?? ''),
                            ])));
                            $personaId = (string) ($designacion['persona_id'] ?? '');
                            $activa = trim((string) ($designacion['hasta'] ?? '')) === '';
                            ?>
                            <tr class="<?= $activa ? '' : 'table-secondary' ?>">
                                <td>
                                    <input type="hidden"
                                           name="designacion_id[<?= e((string) $index) ?>]"
                                           value="<?= e((string) ($designacion['id'] ?? '')) ?>">
                                    <?php if ($personaId !== '') : ?>
                                        <a href="<?= e(url('/personas?q=' . rawurlencode((string) ($designacion['persona_documento'] ?? '')))) ?>">
                                            <?= e($personaNombre !== '' ? $personaNombre : 'Sin nombre') ?>
                                        </a>
                                    <?php else : ?>
                                        <?= e($personaNombre !== '' ? $personaNombre : '—') ?>
                                    <?php endif; ?>
                                    <?php if (!$activa) : ?>
                                        <span class="badge text-bg-secondary ms-1">Finalizada</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e((string) ($designacion['persona_documento'] ?? '')) ?></td>
                                <td>
                                    <select class="form-select form-select-sm"
                                            name="cargo[<?= e((string) $index) ?>]"
                                            <?= $canEdit ? '' : 'disabled' ?>>
                                        <?php foreach ($cargos as $cargo) : ?>
                                            <option value="<?= e($cargo['id']) ?>"
                                                <?= selected($designacion['cargo_id'] ?? '', $cargo['id']) ?>>
                                                <?= e($cargo['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           type="date"
                                           name="desde[<?= e((string) $index) ?>]"
                                           value="<?= e($formatDateInput($designacion['desde'] ?? null)) ?>"
                                           <?= $canEdit ? '' : 'disabled' ?>>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           type="date"
                                           name="hasta[<?= e((string) $index) ?>]"
                                           value="<?= e($formatDateInput($designacion['hasta'] ?? null)) ?>"
                                           <?= $canEdit ? '' : 'disabled' ?>>
                                </td>
                                <?php if ($canEdit) : ?>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger"
                                                type="submit"
                                                onclick="document.getElementById('delete_designacion_id').value='<?= e((string) ($designacion['id'] ?? '')) ?>'; return confirm('¿Eliminar esta designación?');">
                                            Eliminar
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($canEdit) : ?>
                    <button class="btn btn-primary"
                            type="submit"
                            onclick="document.getElementById('delete_designacion_id').value='';">
                        Guardar designaciones
                    </button>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <?php if ($canEdit) : ?>
            <h3 class="h5 mt-4">Agregar designación</h3>
            <form method="post" action="<?= e(url('/sedes/' . rawurlencode($sedeId) . '/designaciones/agregar')) ?>">
                <?= $csrf->field() ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label" for="dni">DNI <span class="text-danger">*</span></label>
                        <input class="form-control"
                               type="text"
                               name="dni"
                               id="dni"
                               inputmode="numeric"
                               pattern="[0-9]{7,8}"
                               maxlength="10"
                               placeholder="Solo números"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="cargo_nuevo">Cargo <span class="text-danger">*</span></label>
                        <select class="form-select" name="cargo" id="cargo_nuevo" required>
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($cargos as $cargo) : ?>
                                <option value="<?= e($cargo['id']) ?>" <?= selected($cargo['id'], '1') ?>>
                                    <?= e($cargo['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="desde_nueva">Desde</label>
                        <input class="form-control" type="date" name="desde" id="desde_nueva" value="">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="hasta_nueva">Hasta</label>
                        <input class="form-control" type="date" name="hasta" id="hasta_nueva" value="">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100" type="submit">Agregar</button>
                    </div>
                </div>
                <p class="form-text mb-0 mt-2">
                    Si la persona no está cargada, el sistema mostrará un error y habrá que crearla primero en Personas.
                </p>
            </form>
        <?php endif; ?>
    </section>
<?php endif; ?>
