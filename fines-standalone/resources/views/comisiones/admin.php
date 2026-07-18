<?php
$isNew = !empty($isNew);
$comisionLabel = trim(implode(' | ', array_filter([
    ($comision['pfid'] ?? '') !== '' ? 'PFID ' . $comision['pfid'] : '',
    $comision['sede_nombre'] ?? '',
    $comision['calendario_label'] ?? '',
])));
$canEdit = $auth->canEdit();
$calendarioBack = ($comision['calendario_id'] ?? '') !== ''
    ? '?calendario=' . rawurlencode((string) $comision['calendario_id'])
    : '';
$formAction = $isNew
    ? url('/comisiones')
    : url('/comisiones/' . rawurlencode((string) $comision['id']));
$docenteNombre = static fn (array $toma): string => trim(implode(' ', array_filter([
    $toma['docente_apellidos'] ?? '',
    $toma['docente_nombres'] ?? '',
])));
$cursoOptionLabel = static fn (array $curso): string => trim(implode(' · ', array_filter([
    $curso['label'] ?? '',
    ($curso['id'] ?? '') !== '' ? '#' . substr((string) $curso['id'], -6) : '',
])));
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/comisiones' . $calendarioBack)) ?>">&larr; Volver a comisiones</a>
        <h1 class="mt-2"><?= $isNew ? 'Nueva comisión' : 'Administrar comisión' ?></h1>
        <p class="text-secondary mb-0">
            <?= e($isNew ? 'Completá los datos para crear una comisión vacía (sin cursos ni tomas).' : ($comisionLabel !== '' ? $comisionLabel : 'Comisión')) ?>
        </p>
    </div>
    <?php if (!$isNew) : ?>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos')) ?>">
                Ver alumnos
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Datos de la comisión</h2>
    <form method="post" action="<?= e($formAction) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="calendario">Calendario</label>
                <select class="form-select" name="calendario" id="calendario" <?= $canEdit ? '' : 'disabled' ?> required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($calendarios as $calendario) : ?>
                        <?php $label = trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? '')); ?>
                        <option value="<?= e($calendario['id']) ?>" <?= selected($comision['calendario_id'] ?? '', $calendario['id']) ?>>
                            <?= e($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="sede">Sede</label>
                <select class="form-select" name="sede" id="sede" <?= $canEdit ? '' : 'disabled' ?> required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($sedes as $sede) : ?>
                        <option value="<?= e($sede['id']) ?>" <?= selected($comision['sede_id'] ?? '', $sede['id']) ?>>
                            <?= e($sede['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="modalidad">Modalidad</label>
                <select class="form-select" name="modalidad" id="modalidad" <?= $canEdit ? '' : 'disabled' ?> required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($modalidades as $modalidad) : ?>
                        <option value="<?= e($modalidad['id']) ?>" <?= selected($comision['modalidad_id'] ?? '', $modalidad['id']) ?>>
                            <?= e($modalidad['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="planificacion">Planificación</label>
                <select class="form-select" name="planificacion" id="planificacion" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($planificaciones as $planificacion) : ?>
                        <option value="<?= e($planificacion['id']) ?>" <?= selected($comision['planificacion_id'] ?? '', $planificacion['id']) ?>>
                            <?= e($planificacion['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    <?= $isNew
                        ? 'Si elegís planificación al crear, se generan los cursos de sus disposiciones. Si la dejás vacía, la comisión queda sin cursos.'
                        : 'Al guardar, se crean cursos faltantes de las disposiciones de esta planificación.' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="turno">Turno</label>
                <select class="form-select" name="turno" id="turno" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">-- Seleccione --</option>
                    <?php foreach (['Mañana', 'Tarde', 'Vespertino'] as $turno) : ?>
                        <option value="<?= e($turno) ?>" <?= selected($comision['turno'] ?? '', $turno) ?>><?= e($turno) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="division">División</label>
                <input class="form-control" type="text" name="division" id="division" value="<?= e($comision['division'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="pfid">PFID</label>
                <input class="form-control" type="text" name="pfid" id="pfid" value="<?= e($comision['pfid'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="comision_siguiente">Comisión siguiente</label>
                <input class="form-control" type="text" name="comision_siguiente" id="comision_siguiente" value="<?= e($comision['comision_siguiente'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
            </div>
            <div class="col-12 d-flex flex-wrap gap-4">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="autorizada" value="1" <?= checked($comision['autorizada'] ?? 0) ?> <?= $canEdit ? '' : 'disabled' ?>>
                    <span class="form-check-label">Autorizada</span>
                </label>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="apertura" value="1" <?= checked($comision['apertura'] ?? 0) ?> <?= $canEdit ? '' : 'disabled' ?>>
                    <span class="form-check-label">Apertura</span>
                </label>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="publicada" value="1" <?= checked($comision['publicada'] ?? 0) ?> <?= $canEdit ? '' : 'disabled' ?>>
                    <span class="form-check-label">Publicada</span>
                </label>
            </div>
            <div class="col-12">
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones" rows="3" <?= $canEdit ? '' : 'readonly' ?>><?= e($comision['observaciones'] ?? '') ?></textarea>
            </div>
            <?php if ($canEdit) : ?>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">
                        <?= $isNew ? 'Crear comisión' : 'Guardar comisión' ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</section>

<?php if ($isNew) : ?>
    <section class="panel mt-3">
        <h2>Cursos y tomas</h2>
        <p class="text-secondary mb-0">
            Después de crear la comisión vas a poder cargar cursos y tomas en la misma pantalla de administración.
        </p>
    </section>
<?php else : ?>

<section class="panel mt-3">
    <h2>Cursos</h2>
    <?php if ($cursos === []) : ?>
        <div class="empty-state mb-3">No hay cursos en esta comisión.</div>
    <?php else : ?>
        <form method="post" action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/cursos')) ?>" id="form-cursos">
            <?= $csrf->field() ?>
            <input type="hidden" name="delete_curso_id" id="delete_curso_id" value="">
            <div class="table-responsive">
                <table class="table table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th>Disposición</th>
                        <th>Hs. cat. curso</th>
                        <th>Hs. cat. dispo</th>
                        <th>Horario</th>
                        <?php if ($canEdit) : ?><th>Acciones</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cursos as $index => $curso) : ?>
                        <tr>
                            <td>
                                <input type="hidden" name="curso_id[<?= e((string) $index) ?>]" value="<?= e($curso['id']) ?>">
                                <select class="form-select form-select-sm" name="disposicion[<?= e((string) $index) ?>]" <?= $canEdit ? '' : 'disabled' ?>>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach ($disposiciones as $disposicion) : ?>
                                        <option value="<?= e($disposicion['id']) ?>" <?= selected($curso['disposicion_id'] ?? '', $disposicion['id']) ?>>
                                            <?= e($disposicion['label']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="max-width: 100px">
                                <input class="form-control form-control-sm" type="number" min="0" name="horas_catedra[<?= e((string) $index) ?>]" value="<?= e((string) ($curso['horas_catedra'] ?? 0)) ?>" <?= $canEdit ? '' : 'readonly' ?>>
                            </td>
                            <td><?= e((string) ($curso['disposicion_horas_catedra'] ?? '?')) ?></td>
                            <td>
                                <input class="form-control form-control-sm" name="descripcion_horario[<?= e((string) $index) ?>]" value="<?= e($curso['descripcion_horario'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
                            </td>
                            <?php if ($canEdit) : ?>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" type="submit" onclick="document.getElementById('delete_curso_id').value='<?= e($curso['id']) ?>'; return confirm('¿Eliminar este curso?');">
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
                <button class="btn btn-primary" type="submit" onclick="document.getElementById('delete_curso_id').value='';">Guardar cursos</button>
            <?php endif; ?>
        </form>
    <?php endif; ?>

    <?php if ($canEdit) : ?>
        <h3 class="h5 mt-4">Añadir curso</h3>
        <form method="post" action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/cursos/agregar')) ?>">
            <?= $csrf->field() ?>
            <div class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label" for="disposicion_nueva">Disposición</label>
                    <select class="form-select" name="disposicion" id="disposicion_nueva" required>
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($disposiciones as $disposicion) : ?>
                            <option value="<?= e($disposicion['id']) ?>"><?= e($disposicion['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="horas_nueva">Hs. cat.</label>
                    <input class="form-control" type="number" min="0" name="horas_catedra" id="horas_nueva" value="">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="horario_nuevo">Horario</label>
                    <input class="form-control" name="descripcion_horario" id="horario_nuevo" value="">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100" type="submit">Agregar</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</section>

<section class="panel mt-3">
    <h2>Tomas</h2>
    <?php if ($tomas === []) : ?>
        <div class="empty-state mb-3">No hay tomas en esta comisión.</div>
    <?php else : ?>
        <form method="post" action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/tomas')) ?>" id="form-tomas">
            <?= $csrf->field() ?>
            <input type="hidden" name="delete_toma_id" id="delete_toma_id" value="">
            <div class="table-responsive">
                <table class="table table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Docente</th>
                        <th>DNI</th>
                        <th>Estado</th>
                        <th>Tipo mov.</th>
                        <th>Contralor</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tomas as $index => $toma) : ?>
                        <?php
                        $telefono = trim((string) ($toma['docente_telefono'] ?? ''));
                        $email = trim((string) ($toma['docente_email'] ?? ''));
                        $emailAbc = trim((string) ($toma['docente_email_abc'] ?? ''));
                        ?>
                        <tr>
                            <td style="min-width: 140px">
                                <input type="hidden" name="toma_id[<?= e((string) $index) ?>]" value="<?= e($toma['id']) ?>">
                                <input class="form-control form-control-sm" type="date" name="fecha_toma[<?= e((string) $index) ?>]" value="<?= e($toma['fecha_toma'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
                            </td>
                            <td style="min-width: 180px">
                                <select class="form-select form-select-sm" name="curso[<?= e((string) $index) ?>]" <?= $canEdit ? '' : 'disabled' ?>>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach ($cursos as $curso) : ?>
                                        <option value="<?= e($curso['id']) ?>" <?= selected($toma['curso_id'] ?? '', $curso['id']) ?>>
                                            <?= e($cursoOptionLabel($curso)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="small">
                                <?php if (($toma['docente_id'] ?? '') !== '') : ?>
                                    <div>
                                        <a href="<?= e(url('/personas/' . rawurlencode((string) $toma['docente_id']) . '/docente')) ?>" target="_blank" rel="noopener">
                                            <?= e($docenteNombre($toma) !== '' ? $docenteNombre($toma) : 'Docente') ?>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div class="text-secondary">Sin docente</div>
                                <?php endif; ?>
                                <?php if ($telefono !== '') : ?>
                                    <div><?= e($telefono) ?></div>
                                <?php endif; ?>
                                <?php if ($email !== '') : ?>
                                    <div><?= e($email) ?></div>
                                <?php endif; ?>
                                <?php if ($emailAbc !== '') : ?>
                                    <div class="text-secondary"><?= e($emailAbc) ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="min-width: 120px">
                                <input class="form-control form-control-sm" name="dni_docente[<?= e((string) $index) ?>]" value="<?= e($toma['docente_documento'] ?? '') ?>" <?= $canEdit ? '' : 'readonly' ?>>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="estado[<?= e((string) $index) ?>]" <?= $canEdit ? '' : 'disabled' ?>>
                                    <option value="">--</option>
                                    <?php foreach ($estadosToma as $estado) : ?>
                                        <option value="<?= e($estado) ?>" <?= selected($toma['estado'] ?? '', $estado) ?>><?= e($estado) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="tipo_movimiento[<?= e((string) $index) ?>]" <?= $canEdit ? '' : 'disabled' ?>>
                                    <option value="">--</option>
                                    <?php foreach ($tiposMovimiento as $tipo) : ?>
                                        <option value="<?= e($tipo) ?>" <?= selected($toma['tipo_movimiento'] ?? '', $tipo) ?>><?= e($tipo) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="estado_contralor[<?= e((string) $index) ?>]" <?= $canEdit ? '' : 'disabled' ?>>
                                    <option value="">--</option>
                                    <?php foreach ($estadosContralor as $estadoContralor) : ?>
                                        <option value="<?= e($estadoContralor) ?>" <?= selected($toma['estado_contralor'] ?? '', $estadoContralor) ?>><?= e($estadoContralor) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 align-items-stretch">
                                    <?php if (($toma['curso_id'] ?? '') !== '') : ?>
                                        <a class="btn btn-sm btn-outline-success" href="<?= e(url('/cursos/' . rawurlencode((string) $toma['curso_id']) . '/planilla')) ?>" title="Planilla de calificación">
                                            Planilla
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($canEdit) : ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="document.getElementById('delete_toma_id').value='<?= e($toma['id']) ?>'; return confirm('¿Eliminar esta toma?');">
                                            Eliminar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($canEdit) : ?>
                <button class="btn btn-primary" type="submit" onclick="document.getElementById('delete_toma_id').value='';">Guardar tomas</button>
            <?php endif; ?>
        </form>
    <?php endif; ?>

    <?php if ($canEdit) : ?>
        <h3 class="h5 mt-4">Agregar toma</h3>
        <?php if ($cursos === []) : ?>
            <div class="alert alert-info mb-0">Primero agregá al menos un curso para poder crear tomas.</div>
        <?php else : ?>
            <form method="post" action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/tomas/agregar')) ?>">
                <?= $csrf->field() ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle app-table">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>DNI docente</th>
                            <th>Estado</th>
                            <th>Tipo mov.</th>
                            <th>Contralor</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input class="form-control form-control-sm" type="date" name="fecha_toma" value="<?= e(date('Y-m-d')) ?>"></td>
                            <td>
                                <select class="form-select form-select-sm" name="curso" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach ($cursos as $curso) : ?>
                                        <option value="<?= e($curso['id']) ?>"><?= e($cursoOptionLabel($curso)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input class="form-control form-control-sm" name="dni_docente" placeholder="DNI" required></td>
                            <td>
                                <select class="form-select form-select-sm" name="estado">
                                    <option value="">--</option>
                                    <?php foreach ($estadosToma as $estado) : ?>
                                        <option value="<?= e($estado) ?>"><?= e($estado) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="tipo_movimiento" required>
                                    <option value="">--</option>
                                    <?php foreach ($tiposMovimiento as $tipo) : ?>
                                        <option value="<?= e($tipo) ?>"><?= e($tipo) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="estado_contralor">
                                    <option value="">--</option>
                                    <?php foreach ($estadosContralor as $estadoContralor) : ?>
                                        <option value="<?= e($estadoContralor) ?>"><?= e($estadoContralor) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><button class="btn btn-sm btn-outline-primary" type="submit">Agregar</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php endif; ?>
