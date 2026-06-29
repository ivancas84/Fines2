<?php
$calendarioLabel = static fn (array $calendario): string => trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? ''));
$tomaEstado = static function (array $curso): string {
    return trim(implode(' / ', array_filter([
        $curso['toma_estado'] ?? '',
        $curso['estado_contralor'] ?? '',
    ])));
};
?>
<div class="page-header">
    <div>
        <h1>Cursos del semestre</h1>
        <p class="text-secondary mb-0"><?= e((string) count($cursos)) ?> cursos consultados.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/informes')) ?>">Volver</a>
</div>

<form class="filters-bar" method="get" action="<?= e(url('/cursos')) ?>">
    <select class="form-select" name="calendario">
        <?php foreach ($calendarios as $calendario) : ?>
            <option value="<?= e($calendario['id']) ?>" <?= selected($selectedCalendario, $calendario['id']) ?>>
                <?= e($calendarioLabel($calendario)) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Consultar</button>
</form>

<?php if ($cursos === []) : ?>
    <div class="empty-state">No se encontraron cursos para este calendario.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>Sede</th>
                <th>Comision</th>
                <th>Tramo</th>
                <th>Asignatura</th>
                <th>Hs cat</th>
                <th>Docente</th>
                <th>Email</th>
                <th>Email ABC</th>
                <th>Telefono</th>
                <th>Fecha toma</th>
                <th>Estado</th>
                <th>Planilla</th>
                <th>Estado planilla</th>
                <th class="text-end">Aprobados</th>
                <th>Ids</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cursos as $curso) : ?>
                <?php
                $asignatura = trim(implode(' ', array_filter([
                    $curso['asignatura_codigo'] ?? '',
                    $curso['asignatura_nombre'] ?? '',
                ])));
                $docente = trim((string) ($curso['docente_nombre'] ?? ''));
                ?>
                <tr>
                    <td><?= e($curso['sede_nombre'] ?? '') ?></td>
                    <td><?= e($curso['pfid'] ?? '') ?></td>
                    <td><?= e($curso['tramo_label'] ?? '') ?></td>
                    <td><?= e($asignatura) ?></td>
                    <td><?= e(($curso['curso_horas_catedra'] ?? '') . '/' . ($curso['disposicion_horas_catedra'] ?? '')) ?></td>
                    <td>
                        <?php if ($docente !== '' && ($curso['docente_id'] ?? '') !== '') : ?>
                            <a href="<?= e(url('/personas/' . rawurlencode((string) $curso['docente_id']) . '/docente')) ?>"><?= e($docente) ?></a>
                        <?php else : ?>
                            <span class="text-secondary">Sin docente</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($curso['docente_email'] ?? '') ?></td>
                    <td><?= e($curso['docente_email_abc'] ?? '') ?></td>
                    <td><?= e($curso['docente_telefono'] ?? '') ?></td>
                    <td><?= e($curso['fecha_toma'] ?? '') ?></td>
                    <td><?= e($tomaEstado($curso)) ?></td>
                    <td><?= e($curso['planilla_numero'] ?? '') ?></td>
                    <td><?= e(($curso['estado_planilla'] ?? '') !== '' ? $curso['estado_planilla'] : 'Sin entregar') ?></td>
                    <td class="text-end"><?= e($curso['cantidad_aprobados'] ?? 0) ?></td>
                    <td class="small text-secondary">
                        Toma <?= e($curso['toma_id'] ?? '') ?><br>
                        Curso <?= e($curso['curso_id'] ?? '') ?><br>
                        Comision <?= e($curso['comision_id'] ?? '') ?><br>
                        Sede <?= e($curso['sede_id'] ?? '') ?>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/comisiones/' . rawurlencode((string) $curso['comision_id']) . '/alumnos')) ?>">
                            Ver alumnos
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<section class="panel mt-3">
    <h2>Criterio</h2>
    <p class="text-secondary mb-0">
        Lista cursos de comisiones autorizadas del calendario seleccionado. La toma activa corresponde a tomas aprobadas
        cuyo estado de contralor no sea Modificar. La cantidad de aprobados cuenta alumnos de la comision con esa
        disposicion aprobada por nota final o CREC.
    </p>
</section>
