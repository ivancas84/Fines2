<div class="page-header">
    <div>
        <h1>Herramientas</h1>
        <p class="text-secondary mb-0">Utilidades de administración, importación y procesos auxiliares.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/calendarios')) ?>">
            <strong>Calendarios</strong>
            <span>Ver, crear y editar períodos lectivos del sistema.</span>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/docentes/procesar-pf')) ?>">
            <strong>Procesar docentes PF</strong>
            <span>Importar XLSX de ProgramaFines: personas y tomas del CENS 462.</span>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/comisiones/procesar-pf')) ?>">
            <strong>Procesar comisiones PF</strong>
            <span>Informe global: horarios de cursos y tomas pendientes.</span>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a class="action-card" href="<?= e(url('/comisiones/procesar-pci')) ?>">
            <strong>Procesar comisiones PCI</strong>
            <span>Informe global PCI: Áreas A–E, horarios y tomas por DNI.</span>
        </a>
    </div>
</div>
