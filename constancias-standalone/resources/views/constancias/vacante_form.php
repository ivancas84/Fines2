<?php
$defaults = form_values($defaults, [
    'nombres',
    'apellidos',
    'numero_documento',
    'fecha',
    'presentado',
    'observaciones',
    'incluir_firmas',
]);
?>
<div class="page-header">
    <div>
        <h1>Constancia de vacante</h1>
        <p class="text-secondary mb-0">Formulario independiente sin datos de Fines.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/constancias')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit" method="post" action="<?= e(url('/constancias/vacante')) ?>">
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
            <label class="form-label form-wide">Observaciones <textarea class="form-control" name="observaciones" rows="3"><?= e($defaults['observaciones'] ?? '') ?></textarea></label>
        </div>
        <button class="btn btn-primary mt-3" type="submit" data-submitting-text="Generando...">Generar constancia</button>
    </form>
</section>
