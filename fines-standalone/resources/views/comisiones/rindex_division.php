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
$fromQuery = $from !== '' ? '?from=' . rawurlencode($from) : '';
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
$sinPfid = !empty($sinPfid);
$columnas = $columnas ?? [];
$filas = $filas ?? [];
$siNo = static fn (mixed $value): string => !empty($value) ? 'Si' : 'No';
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e($backUrl) ?>">&larr; <?= e($backLabel) ?></a>
        <h1 class="mt-2">Rindex división</h1>
        <p class="text-secondary mb-0"><?= e($comisionLabel !== '' ? $comisionLabel : 'Comisión') ?></p>
        <?php if ($planLabel !== '') : ?>
            <p class="small text-secondary mb-0"><?= e($planLabel) ?></p>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']))) ?>">
            Administrar
        </a>
        <a class="btn btn-outline-dark" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/rindex' . $fromQuery)) ?>" title="Rindex de calificaciones de la comisión">
            Rindex
        </a>
        <span class="badge text-bg-primary fs-6 align-self-center">
            <?= e((string) count($filas)) ?> alumnos · <?= e((string) count($columnas)) ?> disposiciones
        </span>
    </div>
</div>

<?php if ($sinPfid) : ?>
    <div class="empty-state">Esta comisión no tiene PFID. No se puede armar el rindex de la división.</div>
<?php else : ?>
    <section class="panel">
        <h2>División</h2>
        <p class="mb-1">Comisiones: <?= e((string) ($comisionesCount ?? 0)) ?></p>
        <p class="mb-1">Comisiones mezcladas: <?= e($siNo($mezcladas ?? false)) ?></p>
        <p class="mb-0">Comisiones de diferente plan: <?= e($siNo($diferentePlan ?? false)) ?></p>
    </section>

    <p class="text-secondary small">
        Disposiciones de los planes del PFID de esta comisión.
        Los alumnos son los de esas comisiones y, si hay comisión siguiente de otro PFID, los de esa cadena.
        Notas aprobadas (nota final ≥ 7 o CREC ≥ 4). El sufijo <strong>c</strong> indica aprobación por CREC.
        Verde = aprobada; rojo = sin nota aprobada registrada.
    </p>

    <?php if ($filas === []) : ?>
        <div class="empty-state">No hay alumnos en las comisiones de esta división.</div>
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
                            <div><?= e($columna['asignatura'] ?? '?') ?></div>
                            <div class="small fw-normal text-secondary"><?= e($columna['detalle'] ?? '') ?></div>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($filas as $fila) : ?>
                    <tr>
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
<?php endif; ?>
