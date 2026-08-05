<?php
$canEdit = $auth->canEdit();
$sortUrl = static function (string $column) use ($sort, $order): string {
    return url('/calendarios?' . http_build_query([
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
        <h1>Calendarios</h1>
        <p class="text-secondary mb-0">
            <?= e((string) count($calendarios)) ?> calendario<?= count($calendarios) === 1 ? '' : 's' ?> en el sistema.
        </p>
    </div>
    <?php if ($canEdit) : ?>
        <div>
            <a class="btn btn-primary" href="<?= e(url('/calendarios/nuevo')) ?>">Nuevo calendario</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if ($calendarios === []) : ?>
    <div class="empty-state">No se encontraron calendarios.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>ID</th>
                <th><a href="<?= e($sortUrl('anio')) ?>">Año</a></th>
                <th><a href="<?= e($sortUrl('semestre')) ?>">Semestre</a></th>
                <th><a href="<?= e($sortUrl('descripcion')) ?>">Descripción</a></th>
                <th><a href="<?= e($sortUrl('inicio')) ?>">Inicio</a></th>
                <th><a href="<?= e($sortUrl('fin')) ?>">Fin</a></th>
                <th><a href="<?= e($sortUrl('comisiones')) ?>">Comisiones</a></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($calendarios as $calendario) : ?>
                <?php
                $id = (string) ($calendario['id'] ?? '');
                $label = trim((string) ($calendario['label'] ?? ''));
                if ($label === '') {
                    $label = trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? ''));
                }
                ?>
                <tr>
                    <td><code class="small"><?= e($id) ?></code></td>
                    <td><?= e((string) ($calendario['anio'] ?? '')) ?></td>
                    <td><?= e((string) ($calendario['semestre'] ?? '')) ?></td>
                    <td><?= e(trim((string) ($calendario['descripcion'] ?? '')) ?: '—') ?></td>
                    <td><?= e($formatFecha($calendario['inicio'] ?? null)) ?></td>
                    <td><?= e($formatFecha($calendario['fin'] ?? null)) ?></td>
                    <td>
                        <?php $count = (int) ($calendario['comisiones_count'] ?? 0); ?>
                        <?php if ($count > 0 && $id !== '') : ?>
                            <a href="<?= e(url('/comisiones?calendario=' . rawurlencode($id))) ?>">
                                <?= e((string) $count) ?>
                            </a>
                        <?php else : ?>
                            <?= e((string) $count) ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="<?= e(url('/calendarios/' . rawurlencode($id))) ?>">
                            <?= $canEdit ? 'Editar' : 'Ver' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
