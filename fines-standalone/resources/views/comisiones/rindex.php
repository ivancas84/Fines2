<?php
$comisionLabel = trim(implode(' | ', array_filter([
    ($comision['pfid'] ?? '') !== '' ? 'PFID ' . $comision['pfid'] : '',
    $comision['sede_nombre'] ?? '',
    $comision['calendario_label'] ?? '',
])));
$planLabel = trim(implode(' ', array_filter([
    $comision['planificacion_label'] ?? '',
    $comision['plan_orientacion'] ?? '',
    $comision['plan_resolucion'] ?? '',
])));
$from = (string) ($from ?? '');
$backUrl = match ($from) {
    'cursos' => url('/cursos' . (($comision['calendario_id'] ?? '') !== ''
        ? '?calendario=' . rawurlencode((string) $comision['calendario_id'])
        : '')),
    'alumnos' => url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos'),
    default => url('/comisiones' . (($comision['calendario_id'] ?? '') !== ''
        ? '?calendario=' . rawurlencode((string) $comision['calendario_id'])
        : '')),
};
$backLabel = match ($from) {
    'cursos' => 'Volver a cursos',
    'alumnos' => 'Volver a alumnos',
    default => 'Volver a comisiones',
};
$columnas = $columnas ?? [];
$filas = $filas ?? [];
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e($backUrl) ?>">&larr; <?= e($backLabel) ?></a>
        <h1 class="mt-2">Rindex comisión</h1>
        <p class="text-secondary mb-0"><?= e($comisionLabel !== '' ? $comisionLabel : 'Comisión') ?></p>
        <?php if ($planLabel !== '') : ?>
            <p class="small text-secondary mb-0"><?= e($planLabel) ?></p>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']))) ?>">
            Administrar
        </a>
        <a class="btn btn-outline-primary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos')) ?>">
            Ver alumnos
        </a>
        <?php if ($auth->canEdit()) : ?>
            <form method="post"
                  action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/reactivar-alumnos')) ?>"
                  onsubmit="return confirm('¿Recalcular activo de los alumnos según calificaciones aprobadas del tramo de la comisión (≥ 3 activan, &lt; 3 desactivan)?');">
                <?= $csrf->field() ?>
                <input type="hidden" name="redirect" value="rindex">
                <input type="hidden" name="from" value="<?= e($from) ?>">
                <button class="btn btn-outline-info" type="submit"
                        title="Activa alumnos con ≥ 3 calificaciones aprobadas del mismo año/semestre de la planificación; desactiva al resto">
                    Reactivar alumnos
                </button>
            </form>
        <?php endif; ?>
        <span class="badge text-bg-primary fs-6 align-self-center">
            <?= e((string) count($filas)) ?> alumnos · <?= e((string) count($columnas)) ?> cursos
        </span>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<p class="text-secondary small">
    Notas aprobadas por disposición (nota final ≥ 7 o CREC ≥ 4). El sufijo <strong>c</strong> indica aprobación por CREC.
    Verde = aprobada; rojo = sin nota aprobada registrada.
    Gris = alumno inactivo en la comisión.
</p>

<?php if ($columnas === [] && $filas === []) : ?>
    <div class="empty-state">Esta comisión no tiene cursos ni alumnos.</div>
<?php elseif ($filas === []) : ?>
    <div class="empty-state">No hay alumnos en esta comisión.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-sm align-middle rindex-table">
            <thead>
            <tr>
                <th class="rindex-sticky-col">Apellidos</th>
                <th class="rindex-sticky-col-2">Nombres</th>
                <th>DNI</th>
                <?php foreach ($columnas as $columna) : ?>
                    <?php
                    $semClass = (string) ($columna['semestre'] ?? '') === '1'
                        ? 'rindex-sem1'
                        : ((string) ($columna['semestre'] ?? '') === '2' ? 'rindex-sem2' : '');
                    ?>
                    <th class="text-center <?= e($semClass) ?>">
                        <div><?= e($columna['asignatura'] ?? '?') ?><?= e($columna['tramo'] ?? '') ?></div>
                        <div class="small fw-normal text-secondary"><?= e($columna['docente'] ?? '?') ?></div>
                    </th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($filas as $fila) : ?>
                <tr class="<?= (int) ($fila['activo'] ?? 0) === 1 ? '' : 'table-secondary' ?>">
                    <td class="rindex-sticky-col">
                        <?php if (($fila['persona_id'] ?? '') !== '') : ?>
                            <a href="<?= e(url('/personas/' . rawurlencode((string) $fila['persona_id']) . '/alumno')) ?>">
                                <?= e($fila['apellidos'] ?? '') ?>
                            </a>
                        <?php else : ?>
                            <?= e($fila['apellidos'] ?? '') ?>
                        <?php endif; ?>
                    </td>
                    <td class="rindex-sticky-col-2">
                        <?php if (($fila['persona_id'] ?? '') !== '') : ?>
                            <a href="<?= e(url('/personas/' . rawurlencode((string) $fila['persona_id']) . '/alumno')) ?>">
                                <?= e($fila['nombres'] ?? '') ?>
                            </a>
                        <?php else : ?>
                            <?= e($fila['nombres'] ?? '') ?>
                        <?php endif; ?>
                    </td>
                    <td><?= e($fila['numero_documento'] ?? '') ?></td>
                    <?php foreach ($fila['notas'] as $nota) : ?>
                        <?php $tieneNota = trim((string) $nota) !== ''; ?>
                        <td class="text-center <?= $tieneNota ? 'rindex-ok' : 'rindex-missing' ?>">
                            <?= e($tieneNota ? (string) $nota : '') ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
