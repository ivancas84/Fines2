<?php
$defaults = form_values($defaults, [
    'nombres',
    'apellidos',
    'numero_documento',
    'fecha',
    'presentado',
    'texto',
    'incluir_firmas',
]);
?>
<div class="page-header">
    <div>
        <h1>Constancia general</h1>
        <p class="text-secondary mb-0">Formulario con texto libre para completar el cuerpo de la constancia.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/constancias')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit" method="post" action="<?= e(url('/constancias/general')) ?>">
        <?= $csrf->field() ?>
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($defaults['nombres'] ?? '') ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($defaults['apellidos'] ?? '') ?>" required></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($defaults['numero_documento'] ?? '') ?>" required></label>
            <label class="form-label">Fecha <input class="form-control" name="fecha" value="<?= e($defaults['fecha'] ?? '') ?>" required></label>
            <label class="form-label">Presentado a <input class="form-control" name="presentado" value="<?= e($defaults['presentado'] ?? '') ?>" required></label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="incluir_firmas" value="1" <?= checked($defaults['incluir_firmas'] ?? '1') ?>>
                <span class="form-check-label">Incluir firmas</span>
            </label>
        </div>

        <div class="mt-4">
            <label class="form-label" for="texto">Texto de la constancia</label>
            <textarea class="form-control" id="texto" name="texto" rows="8" required><?= e($defaults['texto'] ?? '') ?></textarea>
        </div>

        <button class="btn btn-primary mt-3" type="submit" data-submitting-text="Generando...">Generar constancia</button>
    </form>
</section>
