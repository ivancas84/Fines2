<?php
$calendarioLabel = static function (array $calendario): string {
    $label = trim((string) ($calendario['label'] ?? ''));
    if ($label !== '') {
        return $label;
    }

    return trim(implode(' ', array_filter([
        trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '')),
        (string) ($calendario['descripcion'] ?? ''),
    ]))) ?: (string) ($calendario['id'] ?? '');
};
$planLabel = static function (array $plan): string {
    return trim(implode(' - ', array_filter([
        (string) ($plan['orientacion'] ?? ''),
        (string) ($plan['resolucion'] ?? ''),
    ]))) ?: (string) ($plan['id'] ?? '');
};
?>
<div class="page-header">
    <div>
        <h1>Contralor</h1>
        <p class="text-secondary mb-0">Listado para copiar y pegar, equivalente al script de tomas para contralor.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="<?= e(url('/planillas-docente')) ?>">Planillas docente</a>
        <a class="btn btn-outline-secondary" href="<?= e(url('/informes')) ?>">Volver</a>
    </div>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <h2>Filtros</h2>
    <p class="text-secondary">
        Elegí <strong>calendario</strong> o <strong>planilla docente</strong>, no ambos.
        El plan es opcional y filtra por la planificación de la comisión.
    </p>
    <ul class="text-secondary">
        <li>Calendario (planilla vacía): tomas aprobadas con contralor «Pasar» y sin planilla docente.</li>
        <li>Planilla docente (calendario vacío): tomas asignadas a esa planilla.</li>
    </ul>
    <form method="get" action="<?= e(url('/informes/contralor')) ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="calendario">Calendario</label>
                <select class="form-select" name="calendario" id="calendario">
                    <option value="">—</option>
                    <?php foreach ($calendarios as $calendario) : ?>
                        <option value="<?= e((string) $calendario['id']) ?>" <?= selected($selectedCalendario, $calendario['id']) ?>>
                            <?= e($calendarioLabel($calendario)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="plan">Plan</label>
                <select class="form-select" name="plan" id="plan">
                    <option value="">Todos los planes</option>
                    <?php foreach ($planes as $plan) : ?>
                        <option value="<?= e((string) $plan['id']) ?>" <?= selected($selectedPlan, $plan['id']) ?>>
                            <?= e($planLabel($plan)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="planilla_docente">Planilla docente</label>
                <select class="form-select" name="planilla_docente" id="planilla_docente">
                    <option value="">—</option>
                    <?php foreach ($planillas as $planilla) : ?>
                        <option value="<?= e((string) $planilla['id']) ?>" <?= selected($selectedPlanilla, $planilla['id']) ?>>
                            <?= e((string) ($planilla['label'] ?? $planilla['numero'] ?? $planilla['id'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit" name="consultar" value="1">Generar listado</button>
            </div>
        </div>
    </form>
</section>

<?php if (!empty($queryOk)) : ?>
    <section class="panel">
        <h2>Listado</h2>
        <?php if ($rows === []) : ?>
            <div class="empty-state">No hay tomas para los filtros indicados.</div>
        <?php else : ?>
            <p class="text-secondary"><?= e((string) count($rows)) ?> filas. Seleccioná la tabla y copiá (Ctrl+C).</p>
            <div class="table-responsive">
                <table class="table table-bordered table-sm contralor-copy-table">
                    <tbody>
                    <?php foreach ($rows as $cells) : ?>
                        <tr>
                            <?php foreach ($cells as $cell) : ?>
                                <td><?= e($cell) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $canAsignar = $auth->canEdit()
                && $selectedCalendario !== ''
                && $selectedPlanilla === ''
                && $rows !== [];
            ?>
            <?php if ($canAsignar) : ?>
                <hr>
                <h3 class="h5">Asignar planilla docente</h3>
                <p class="text-secondary">
                    Estas tomas no tienen planilla. Elegí una planilla docente para asignársela.
                    Solo se actualizan las tomas del listado (aprobadas, contralor «Pasar», sin planilla).
                </p>
                <form method="post"
                      action="<?= e(url('/informes/contralor/asignar-planilla')) ?>"
                      onsubmit="return confirm('¿Asignar la planilla seleccionada a <?= e((string) count($rows)) ?> toma(s) sin planilla?')">
                    <?= $csrf->field() ?>
                    <input type="hidden" name="calendario" value="<?= e($selectedCalendario) ?>">
                    <input type="hidden" name="plan" value="<?= e($selectedPlan) ?>">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label" for="asignar_planilla_id">Planilla docente</label>
                            <select class="form-select" name="asignar_planilla_id" id="asignar_planilla_id" required>
                                <option value="">Seleccioná una planilla</option>
                                <?php foreach ($planillas as $planilla) : ?>
                                    <option value="<?= e((string) $planilla['id']) ?>">
                                        <?= e((string) ($planilla['label'] ?? $planilla['numero'] ?? $planilla['id'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit">Asignar a estas tomas</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>
