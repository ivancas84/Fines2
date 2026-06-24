<?php
$boolLabel = static fn (mixed $value): string => (int) $value === 1 ? 'Si' : 'No';
$sortUrl = static function (string $column) use ($selectedCalendario, $soloAutorizadas, $sort, $order): string {
    $params = [
        'calendario' => $selectedCalendario,
        'sort' => $column,
        'order' => $sort === $column && $order === 'asc' ? 'desc' : 'asc',
    ];
    if ($soloAutorizadas) {
        $params['autorizada'] = '1';
    }
    return url('/comisiones?' . http_build_query($params));
};
?>
<div class="page-header">
    <div>
        <h1>Comisiones</h1>
        <p class="text-secondary mb-0"><?= e((string) count($comisiones)) ?> comisiones consultadas.</p>
    </div>
</div>

<form class="filters-bar" method="get" action="<?= e(url('/comisiones')) ?>">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="order" value="<?= e($order) ?>">
    <select class="form-select" name="calendario">
        <?php foreach ($calendarios as $calendario) : ?>
            <?php $label = trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? '')); ?>
            <option value="<?= e($calendario['id']) ?>" <?= selected($selectedCalendario, $calendario['id']) ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <label class="form-check">
        <input class="form-check-input" type="checkbox" name="autorizada" value="1" <?= checked($soloAutorizadas) ?>>
        <span class="form-check-label">Solo autorizadas</span>
    </label>
    <button class="btn btn-primary" type="submit">Consultar</button>
</form>

<?php if ($comisiones === []) : ?>
    <div class="empty-state">No se encontraron comisiones para este calendario.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th><a href="<?= e($sortUrl('nombre')) ?>">Nombre</a></th>
                <th>Domicilio</th>
                <th><a href="<?= e($sortUrl('pfid')) ?>">PFID</a></th>
                <th><a href="<?= e($sortUrl('planificacion')) ?>">Planificacion</a></th>
                <th>Autorizada</th>
                <th><a href="<?= e($sortUrl('apertura')) ?>">Apertura</a></th>
                <th><a href="<?= e($sortUrl('turno')) ?>">Turno</a></th>
                <th>Alumnos</th>
                <th>Siguiente</th>
                <th>Referentes</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($comisiones as $comision) : ?>
                <?php
                $planificacion = trim(($comision['planificacion_label'] ?? '') . ' ' . ($comision['plan_label'] ?? ''));
                $siguienteTramo = ($comision['comision_siguiente_anio'] ?? '') !== '' && ($comision['comision_siguiente_semestre'] ?? '') !== ''
                    ? ($comision['comision_siguiente_anio'] . ' ' . $comision['comision_siguiente_semestre'])
                    : '';
                $siguiente = trim(implode(' - ', array_filter([$comision['comision_siguiente_pfid'] ?? '', $siguienteTramo])));
                ?>
                <tr>
                    <td><?= e($comision['sede_nombre'] ?? '?') ?></td>
                    <td><?= e($comision['domicilio_label'] ?? '?') ?></td>
                    <td><?= e($comision['pfid'] ?? '') ?></td>
                    <td><?= e($planificacion ?: '?') ?></td>
                    <td><?= e($boolLabel($comision['autorizada'] ?? 0)) ?></td>
                    <td><?= e($boolLabel($comision['apertura'] ?? 0)) ?></td>
                    <td><?= e($comision['turno'] ?? '') ?></td>
                    <td>
                        <a href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos')) ?>">
                            <?= e(($comision['cantidad_alumnos'] ?? 0) . '/' . ($comision['cantidad_alumnos_activos'] ?? 0)) ?>
                        </a>
                    </td>
                    <td><?= e($siguiente) ?></td>
                    <td><?= e($comision['referentes_label'] ?? 'Sin Referentes') ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos')) ?>">
                            Ver alumnos
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
