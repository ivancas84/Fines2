<div class="page-header">
    <div>
        <h1>Personas</h1>
        <p class="text-secondary mb-0">Busqueda por apellido, nombre, DNI, telefono o email.</p>
    </div>
    <?php if ($auth->canEdit()) : ?>
        <div>
            <a class="btn btn-primary" href="<?= e(url('/personas/nueva')) ?>">Nueva persona</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<form class="filters-bar" method="get" action="<?= e(url('/personas')) ?>">
    <input
        class="form-control"
        type="search"
        name="q"
        value="<?= e($search) ?>"
        placeholder="Buscar persona"
        hx-get="<?= e(url('/personas')) ?>"
        hx-trigger="keyup changed delay:350ms"
        hx-target="#personas-results"
        hx-push-url="true"
    >
    <button class="btn btn-primary" type="submit">Buscar</button>
</form>

<div id="personas-results">
    <?php require __DIR__ . '/_results.php'; ?>
</div>
