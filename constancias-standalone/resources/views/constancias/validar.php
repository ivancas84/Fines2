<section class="validation-page">
    <header class="validation-topbar">
        <img class="validation-title-image" src="<?= e(url('/assets/constancias-doc.png')) ?>" alt="Constancias">
        <div class="validation-school-name"><?= e($constancia['establecimiento_nombre'] ?? '') ?></div>
    </header>

    <div class="validation-panel">
        <?php if ($constancia === null) : ?>
            <h1>Constancia no encontrada</h1>
            <p class="text-secondary mb-0">El identificador o la clave no corresponden a una constancia activa.</p>
        <?php else : ?>
            <div class="validation-header">
                <div>
                    <h1><?= e($constancia['titulo'] ?? 'Constancia') ?></h1>
                    <p class="text-secondary mb-0">Emitida el <?= e($constancia['creado_en'] ?? '') ?></p>
                </div>
                <span class="badge text-bg-success">Valida</span>
            </div>
            <p><?= e($constancia['descripcion'] ?? '') ?></p>
            <a class="btn btn-primary" href="<?= e(url('/validar-constancia/descargar?id=' . rawurlencode((string) $id) . '&clave=' . rawurlencode((string) $clave))) ?>">Descargar PDF</a>
        <?php endif; ?>
    </div>
</section>
