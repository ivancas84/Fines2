<?php
$claveIngresada = trim((string) ($clave ?? '')) !== '';
$establecimiento = trim((string) ($constancia['establecimiento_nombre'] ?? ''));
?>
<section class="validation-page">
    <div class="validation-panel">
        <div class="validation-card-brand">
            <img class="validation-brand-image" src="<?= e(url('/assets/abc-constancias-logo.png')) ?>" alt="ABC Constancias">
            <?php if ($establecimiento !== '') : ?>
                <div class="validation-school-name"><?= e($establecimiento) ?></div>
            <?php endif; ?>
        </div>

        <?php if ($constancia === null) : ?>
            <?php if ($claveIngresada) : ?>
                <h1>Constancia no encontrada</h1>
                <p class="text-secondary mb-0">El identificador o la clave no corresponden a una constancia activa.</p>
            <?php else : ?>
                <h1>Validar Constancia</h1>
                <p class="text-secondary mb-0">Ingrese clave para validar una constancia.</p>
            <?php endif; ?>
            <form class="validation-key-form" method="get" action="<?= e(url('/validar-constancia')) ?>">
                <label class="form-label w-100">Clave de verificaci&oacute;n
                    <input class="form-control" name="clave" value="<?= e(form_value('clave', $clave ?? '')) ?>" autocomplete="off" maxlength="24" required>
                </label>
                <button class="btn btn-primary" type="submit">Validar</button>
            </form>
        <?php else : ?>
            <div class="validation-header">
                <div>
                    <h1><?= e($constancia['titulo'] ?? 'Constancia') ?></h1>
                    <p class="text-secondary mb-0">Emitida el <?= e($constancia['creado_en'] ?? '') ?></p>
                    <p class="text-secondary mb-0">Clave de verificaci&oacute;n: <?= e($constancia['clave'] ?? '') ?></p>
                </div>
                <span class="badge <?= !empty($vencida) ? 'text-bg-danger' : 'text-bg-success' ?>"><?= !empty($vencida) ? 'Vencida' : 'V&aacute;lida' ?></span>
            </div>
            <div class="validation-body"><?= constancia_html($constancia['descripcion'] ?? '') ?></div>
            <?php if (empty($vencida)) : ?>
                <div class="validation-actions">
                    <a class="btn btn-primary" href="<?= e(url('/validar-constancia/descargar?clave=' . rawurlencode((string) $clave))) ?>">Descargar PDF</a>
                    <a class="btn btn-outline-primary" href="<?= e(url('/validar-constancia')) ?>">Validar otra</a>
                </div>
            <?php else : ?>
                <div class="validation-actions">
                    <p class="text-danger mb-0">Esta constancia super&oacute; los 30 d&iacute;as de vigencia.</p>
                    <a class="btn btn-outline-primary" href="<?= e(url('/validar-constancia')) ?>">Validar otra</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
