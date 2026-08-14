<div class="page-header">
    <div>
        <h1>Inicio</h1>
        <p class="text-secondary mb-0">Administracion de Fines desde una app PHP standalone.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/personas')) ?>">
            <strong>Buscar personas</strong>
            <span>Consultar alumnos, editar datos y revisar comisiones.</span>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/comisiones')) ?>">
            <strong>Comisiones</strong>
            <span>Listar comisiones por calendario, autorizacion y orden.</span>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/herramientas')) ?>">
            <strong>Herramientas</strong>
            <span>Calendarios e importaciones PF (docentes y comisiones).</span>
        </a>
    </div>
</div>
