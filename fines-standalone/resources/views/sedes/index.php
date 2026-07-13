<?php
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
</div>

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
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sedes as $sede) : ?>
                <tr>
                    <td><?= e($sede['numero'] ?? '') ?></td>
                    <td><?= e($sede['nombre'] ?? '') ?></td>
                    <td><?= e($sede['cens'] ?? '') ?></td>
                    <td><?= e($formatFecha($sede['fecha_traspaso'] ?? null)) ?></td>
                    <td><?= e(trim((string) ($sede['domicilio_label'] ?? '')) ?: '—') ?></td>
                    <td><?= e($sede['referentes_label'] ?? 'Sin Referentes') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
