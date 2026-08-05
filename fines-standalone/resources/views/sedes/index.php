<?php
$canEdit = $auth->canEdit();
$sortUrl = static function (string $column) use ($sort, $order): string {
    return url('/sedes?' . http_build_query([
        'sort' => $column,
        'order' => $sort === $column && $order === 'asc' ? 'desc' : 'asc',
    ]));
};

$formatFecha = static function (mixed $value): string {
    $raw = trim((string) ($value ?? ''));
    if ($raw === '') {
        return '';
    }

    $date = date_create($raw);
    return $date instanceof DateTimeInterface ? $date->format('d/m/Y') : $raw;
};
?>
<div class="page-header">
    <div>
        <h1>Sedes</h1>
        <p class="text-secondary mb-0"><?= e((string) count($sedes)) ?> sedes cargadas en el sistema.</p>
    </div>
    <?php if ($canEdit) : ?>
        <div>
            <a class="btn btn-primary" href="<?= e(url('/sedes/nueva')) ?>">Nueva sede</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if ($sedes === []) : ?>
    <div class="empty-state">No se encontraron sedes.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th><a href="<?= e($sortUrl('numero')) ?>">Numero</a></th>
                <th><a href="<?= e($sortUrl('nombre')) ?>">Nombre</a></th>
                <th><a href="<?= e($sortUrl('cens')) ?>">CENS</a></th>
                <th><a href="<?= e($sortUrl('fecha_traspaso')) ?>">Fecha traspaso</a></th>
                <th>Domicilio</th>
                <th>Referentes</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sedes as $sede) : ?>
                <?php $id = (string) ($sede['id'] ?? ''); ?>
                <tr>
                    <td><?= e($sede['numero'] ?? '') ?></td>
                    <td><?= e($sede['nombre'] ?? '') ?></td>
                    <td><?= e($sede['cens'] ?? '') ?></td>
                    <td><?= e($formatFecha($sede['fecha_traspaso'] ?? null)) ?></td>
                    <td><?= e(trim((string) ($sede['domicilio_label'] ?? '')) ?: '—') ?></td>
                    <td><?= e($sede['referentes_label'] ?? 'Sin Referentes') ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="<?= e(url('/sedes/' . rawurlencode($id))) ?>">
                            <?= $canEdit ? 'Editar' : 'Ver' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
