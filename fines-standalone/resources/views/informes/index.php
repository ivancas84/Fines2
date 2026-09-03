<div class="page-header">
    <div>
        <h1>Informes</h1>
        <p class="text-secondary mb-0">Consultas operativas del sistema standalone.</p>
    </div>
</div>

<section class="panel">
    <h2>Disponibles</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>Informe</th>
                <th>Descripcion</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Egresados por calendario</td>
                <td>Cantidad de alumnos en comisiones de tramo 3-2 con al menos 5 calificaciones aprobadas del tramo 3-2.</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/informes/egresados')) ?>">Abrir</a>
                </td>
            </tr>
            <tr>
                <td>Contralor</td>
                <td>Listado para copiar y pegar de tomas sin planilla (por calendario) o de una planilla docente.</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/informes/contralor')) ?>">Abrir</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</section>
