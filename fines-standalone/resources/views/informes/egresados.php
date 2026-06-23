<div class="page-header">
    <div>
        <h1>Egresados por calendario</h1>
        <p class="text-secondary mb-0">Agrupado por calendario.anio y calendario.semestre.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/informes')) ?>">Volver</a>
</div>

<section class="panel">
    <h2>Resultados</h2>
    <?php if ($rows === []) : ?>
        <div class="empty-state">No se encontraron egresados con el criterio definido.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle app-table">
                <thead>
                <tr>
                    <th>Calendario</th>
                    <th>Anio</th>
                    <th>Semestre</th>
                    <th class="text-end">Cantidad egresados</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= e(($row['anio'] ?? '') . '-' . ($row['semestre'] ?? '')) ?></td>
                        <td><?= e($row['anio'] ?? '') ?></td>
                        <td><?= e($row['semestre'] ?? '') ?></td>
                        <td class="text-end"><?= e($row['cantidad_egresados'] ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Criterio</h2>
    <p class="text-secondary mb-0">
        Se cuentan alumnos asociados a una comision cuya planificacion es anio 3 semestre 2.
        Para considerarlos egresados deben tener al menos 5 disposiciones aprobadas del tramo 3-2
        con nota final mayor o igual a 7 o CREC mayor o igual a 4.
    </p>
</section>
