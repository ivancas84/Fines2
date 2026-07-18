<?php
$cursoLabel = trim(implode(' | ', array_filter([
    ($curso['pfid'] ?? '') !== '' ? 'PFID ' . $curso['pfid'] : '',
    $curso['sede_nombre'] ?? '',
    trim(implode(' ', array_filter([
        $curso['asignatura_codigo'] ?? '',
        $curso['asignatura_nombre'] ?? '',
    ]))),
    $curso['tramo_label'] ?? '',
])));
$calendarioLabel = trim(implode(' ', array_filter([
    $curso['calendario_label'] ?? '',
    $curso['calendario_descripcion'] ?? '',
])));
$docenteLabel = trim((string) ($curso['docente_nombre'] ?? ''));
$estadoPlanilla = trim((string) ($curso['estado_planilla'] ?? ''));
$estadoPlanillaLabel = $estadoPlanilla !== '' ? $estadoPlanilla : 'Sin entregar';
$tomaId = trim((string) ($curso['toma_id'] ?? ''));
$canSave = is_array($preview) && (int) ($preview['with_changes'] ?? 0) > 0;
$yaEntregada = mb_strtolower($estadoPlanilla) === 'entregada';
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/cursos' . ($calendarioQuery ?? ''))) ?>">&larr; Volver a cursos</a>
        <h1 class="mt-2">Cargar planilla de calificación</h1>
        <p class="text-secondary mb-0"><?= e($cursoLabel !== '' ? $cursoLabel : 'Curso') ?></p>
        <?php if ($calendarioLabel !== '') : ?>
            <p class="small text-secondary mb-0"><?= e($calendarioLabel) ?></p>
        <?php endif; ?>
        <?php if ($docenteLabel !== '') : ?>
            <p class="small text-secondary mb-0">Docente: <?= e($docenteLabel) ?></p>
        <?php endif; ?>
        <p class="small mb-0">
            Estado planilla:
            <span class="badge <?= $yaEntregada ? 'text-bg-success' : 'text-bg-secondary' ?>">
                <?= e($estadoPlanillaLabel) ?>
            </span>
            <?php if (($curso['planilla_numero'] ?? '') !== '') : ?>
                <span class="text-secondary">· N° <?= e((string) $curso['planilla_numero']) ?></span>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php if (($curso['comision_id'] ?? '') !== '') : ?>
            <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones/' . rawurlencode((string) $curso['comision_id']) . '/alumnos')) ?>">
                Ver alumnos
            </a>
        <?php endif; ?>
        <?php if ($tomaId !== '' && $auth->canEdit()) : ?>
            <form method="post" action="<?= e(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla/entregada')) ?>"
                  onsubmit="return confirm('¿Marcar la planilla de esta toma como entregada?');">
                <?= $csrf->field() ?>
                <input type="hidden" name="return" value="planilla">
                <button class="btn btn-outline-success" type="submit" <?= $yaEntregada ? 'title="Ya está entregada; podés reconfirmar"' : '' ?>>
                    Marcar entregada
                </button>
            </form>
        <?php elseif ($tomaId === '') : ?>
            <span class="btn btn-outline-secondary disabled" title="Sin toma activa">Sin toma activa</span>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Datos de la planilla</h2>
    <ul class="text-secondary">
        <li>Copiar y pegar desde una planilla de calificación (abrirla desde Word).</li>
        <li>Copiar y pegar desde Excel con encabezados (nombres, apellidos, documento y nota; el resto se ignora).</li>
        <li>Formatos: <strong>PF2</strong> (DNI / Alumno / Promedio), <strong>XLSX</strong> (columnas libres) o <strong>PF</strong> (columna Nombre + Final).</li>
        <li>Solo se importan notas mayores o iguales a 7.</li>
    </ul>

    <form method="post" action="<?= e(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla/consultar')) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="format">Formato</label>
                <select class="form-select" name="format" id="format">
                    <?php foreach ($formats as $format) : ?>
                        <option value="<?= e($format) ?>" <?= selected($selectedFormat, $format) ?>><?= e($format) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-9">
                <label class="form-label" for="observaciones">Observaciones</label>
                <input class="form-control" type="text" name="observaciones" id="observaciones" value="<?= e($observaciones) ?>">
            </div>
            <div class="col-12">
                <label class="form-label" for="data">Datos</label>
                <textarea class="form-control font-monospace" name="data" id="data" rows="12" placeholder="Pegá aquí las filas con encabezados..."><?= e($rawData) ?></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Consultar</button>
            </div>
        </div>
    </form>
</section>

<?php if (is_array($preview)) : ?>
    <section class="panel mt-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h2 class="mb-1">Resultado de la consulta</h2>
                <p class="text-secondary mb-0">
                    <?= e((string) $preview['total']) ?> filas ·
                    <?= e((string) $preview['with_changes']) ?> con cambios ·
                    <?= e((string) $preview['errors']) ?> con error
                </p>
            </div>
            <?php if ($canSave && $auth->canEdit()) : ?>
                <form method="post" action="<?= e(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla/guardar')) ?>">
                    <?= $csrf->field() ?>
                    <input type="hidden" name="format" value="<?= e($selectedFormat) ?>">
                    <input type="hidden" name="observaciones" value="<?= e($observaciones) ?>">
                    <textarea name="data" class="d-none" aria-hidden="true"><?= e($rawData) ?></textarea>
                    <button class="btn btn-success" type="submit">Guardar cambios</button>
                </form>
            <?php elseif ($canSave) : ?>
                <div class="alert alert-warning mb-0 py-2">Tenés permisos de solo lectura; no podés guardar.</div>
            <?php elseif ((int) ($preview['total'] ?? 0) > 0) : ?>
                <div class="alert alert-info mb-0 py-2">No hay cambios nuevos para registrar.</div>
            <?php endif; ?>
        </div>

        <?php if (($preview['items'] ?? []) === []) : ?>
            <div class="empty-state">No se detectaron filas en los datos pegados.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Alumno</th>
                        <th>Nota</th>
                        <th>Acciones previstas</th>
                        <th>Detalle</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($preview['items'] as $item) : ?>
                        <?php
                        $ok = !empty($item['ok']);
                        $hasChanges = !empty($item['has_changes']);
                        $rowClass = !$ok ? 'calificacion-desaprobada' : ($hasChanges ? 'calificacion-aprobada' : '');
                        $parsed = $item['parsed'] ?? null;
                        ?>
                        <tr class="<?= e($rowClass) ?>">
                            <td><?= e((string) ($item['row_number'] ?? '')) ?></td>
                            <td>
                                <?php if (!$ok) : ?>
                                    <span class="badge text-bg-danger">Error</span>
                                <?php elseif ($hasChanges) : ?>
                                    <span class="badge text-bg-success">Cambio</span>
                                <?php else : ?>
                                    <span class="badge text-bg-secondary">Sin cambios</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $personaId = trim((string) ($item['persona_id'] ?? ''));
                                $alumnoNombre = is_array($parsed)
                                    ? trim(($parsed['apellidos'] ?? '') . ', ' . ($parsed['nombres'] ?? ''))
                                    : (string) ($item['label'] ?? '');
                                ?>
                                <?php if ($personaId !== '') : ?>
                                    <div>
                                        <a href="<?= e(url('/personas/' . rawurlencode($personaId) . '/alumno')) ?>" target="_blank" rel="noopener">
                                            <?= e($alumnoNombre !== '' ? $alumnoNombre : 'Ver alumno') ?>
                                        </a>
                                    </div>
                                <?php elseif (is_array($parsed)) : ?>
                                    <div><?= e($alumnoNombre) ?></div>
                                <?php else : ?>
                                    <div><?= e($alumnoNombre) ?></div>
                                <?php endif; ?>
                                <?php if (is_array($parsed)) : ?>
                                    <div class="small text-secondary">DNI <?= e((string) ($parsed['numero_documento'] ?? '')) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= e(is_array($parsed) ? (string) ($parsed['nota'] ?? '') : '') ?></td>
                            <td>
                                <?php if (($item['actions'] ?? []) !== []) : ?>
                                    <ul class="mb-0 small ps-3">
                                        <?php foreach ($item['actions'] as $action) : ?>
                                            <li><?= e($action) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    <span class="text-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?php if (!$ok) : ?>
                                    <span class="text-danger"><?= e((string) ($item['error'] ?? 'Error desconocido')) ?></span>
                                <?php else : ?>
                                    <span class="text-secondary">OK</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
