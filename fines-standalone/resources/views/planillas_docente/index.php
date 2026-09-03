<?php
$canEdit = $auth->canEdit();
$sortUrl = static function (string $column) use ($sort, $order): string {
    return url('/planillas-docente?' . http_build_query([
        'sort' => $column,
        'order' => $sort === $column && $order === 'asc' ? 'desc' : 'asc',
    ]));
};

$formatFecha = static function (mixed $value): string {
    $raw = trim((string) ($value ?? ''));
    if ($raw === '') {
        return '—';
    }

    $date = date_create($raw);
    return $date instanceof DateTimeInterface ? $date->format('d/m/Y') : $raw;
};
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/herramientas')) ?>">&larr; Volver a herramientas</a>
        <h1 class="mt-2">Planillas docente</h1>
        <p class="text-secondary mb-0">
            <?= e((string) count($planillas)) ?> planilla<?= count($planillas) === 1 ? '' : 's' ?> en el sistema.
        </p>
    </div>
    <?php if ($canEdit) : ?>
        <div>
            <a class="btn btn-primary" href="<?= e(url('/planillas-docente/nueva')) ?>">Nueva planilla</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if ($planillas === []) : ?>
    <div class="empty-state">No se encontraron planillas docente.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th><a href="<?= e($sortUrl('numero')) ?>">Número</a></th>
                <th><a href="<?= e($sortUrl('insertado')) ?>">Insertado</a></th>
                <th><a href="<?= e($sortUrl('fecha_contralor')) ?>">Fecha contralor</a></th>
                <th><a href="<?= e($sortUrl('fecha_consejo')) ?>">Fecha consejo</a></th>
                <th><a href="<?= e($sortUrl('tomas')) ?>">Tomas</a></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($planillas as $planilla) : ?>
                <?php $id = (string) ($planilla['id'] ?? ''); ?>
                <tr>
                    <td><?= e((string) ($planilla['numero'] ?? '')) ?></td>
                    <td><?= e($formatFecha($planilla['insertado'] ?? null)) ?></td>
                    <td><?= e($formatFecha($planilla['fecha_contralor'] ?? null)) ?></td>
                    <td><?= e($formatFecha($planilla['fecha_consejo'] ?? null)) ?></td>
                    <td>
                        <?php $count = (int) ($planilla['tomas_count'] ?? 0); ?>
                        <?php if ($count > 0 && $id !== '') : ?>
                            <a href="<?= e(url('/informes/contralor?planilla_docente=' . rawurlencode($id) . '&consultar=1')) ?>">
                                <?= e((string) $count) ?>
                            </a>
                        <?php else : ?>
                            <?= e((string) $count) ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="<?= e(url('/planillas-docente/' . rawurlencode($id))) ?>">
                            <?= $canEdit ? 'Editar' : 'Ver' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
