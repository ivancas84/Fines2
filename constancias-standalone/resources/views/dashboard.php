<div class="page-header">
    <div>
        <h1>Constancias emitidas</h1>
        <p class="text-secondary mb-0"><?= e($establecimiento['nombre'] ?? 'Sin establecimiento configurado') ?></p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('/constancias/alumno-regular/nueva')) ?>">Generar alumno regular</a>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>

<section class="panel">
    <?php if (($constancias ?? []) === []) : ?>
        <div class="empty-state">No hay constancias emitidas.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover app-table">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Titular</th>
                    <th>DNI</th>
                    <th>Titulo</th>
                    <th>Origen</th>
                    <th>Validacion</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($constancias as $constancia) : ?>
                    <tr>
                        <td><?= e($constancia['creado_en'] ?? '') ?></td>
                        <td><?= e(trim(($constancia['apellidos'] ?? '') . ', ' . ($constancia['nombres'] ?? ''), ', ')) ?></td>
                        <td><?= e($constancia['numero_documento'] ?? '') ?></td>
                        <td><?= e($constancia['titulo'] ?? '') ?></td>
                        <td><?= e($constancia['origen_sistema'] ?? '') ?></td>
                        <td>
                            <?php if (empty($constancia['anulado_en'])) : ?>
                                <a href="<?= e(url('/validar-constancia?id=' . rawurlencode((string) $constancia['id']) . '&clave=' . rawurlencode((string) $constancia['clave']))) ?>" target="_blank" rel="noopener">Abrir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
