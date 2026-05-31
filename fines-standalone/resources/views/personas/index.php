<div class="page-header">
    <div>
        <h1>Personas</h1>
        <p class="text-secondary mb-0">Busqueda por apellido, nombre, DNI, telefono o email.</p>
    </div>
</div>

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
