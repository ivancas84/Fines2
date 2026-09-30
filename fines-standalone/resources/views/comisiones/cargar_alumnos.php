<?php
$canEdit = $auth->canEdit();
$report = is_array($report ?? null) ? $report : null;
$levelClass = static fn (string $level): string => import_log_level_class($level);
$comisionLabel = trim(implode(' | ', array_filter([
    ($comision['pfid'] ?? '') !== '' ? 'PFID ' . $comision['pfid'] : '',
    $comision['sede_nombre'] ?? '',
    $comision['calendario_label'] ?? '',
])));
$planLabel = trim(implode(' ', array_filter([
    $comision['planificacion_label'] ?? '',
    $comision['plan_orientacion'] ?? '',
    $comision['plan_resolucion'] ?? '',
])));
$backCalendario = ($comision['calendario_id'] ?? '') !== ''
    ? '?calendario=' . rawurlencode((string) $comision['calendario_id'])
    : '';
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/comisiones' . $backCalendario)) ?>">&larr; Volver a comisiones</a>
        <h1 class="mt-2">Cargar alumnos</h1>
        <p class="text-secondary mb-0"><?= e($comisionLabel !== '' ? $comisionLabel : 'Comisión') ?></p>
        <?php if ($planLabel !== '') : ?>
            <p class="small text-secondary mb-0"><?= e($planLabel) ?></p>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']))) ?>">
            Administrar
        </a>
        <a class="btn btn-outline-primary" href="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/alumnos')) ?>">
            Ver alumnos
        </a>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Nómina</h2>
    <p class="text-secondary">Copiá y pegá desde Excel, con encabezados. Pueden estar en cualquier orden. Los que empiezan con <code>_</code> se ignoran.</p>
    <ul class="text-secondary">
        <li><code>nombres</code> (obligatorio para personas nuevas)</li>
        <li><code>apellidos</code></li>
        <li><code>cuil_dni</code> (obligatorio; también acepta <code>dni_cuil</code>). DNI de 7 u 8 dígitos, o CUIL de 11.</li>
        <li><code>fecha_nacimiento</code> en formato aaaa-mm-dd</li>
        <li><code>anio_ingreso</code></li>
        <li><code>modulo</code> (impar: semestre 1; par: semestre 2)</li>
        <li><code>observaciones</code></li>
        <li><code>tiene_dni</code>, <code>tiene_partida</code>, <code>tiene_constancia</code>, <code>tiene_certificado</code>, <code>previas_completas</code></li>
    </ul>
    <p class="text-secondary">
        Si la persona no existe, se crea. Si el alumno es nuevo en la comisión, entra como <strong>Ingresante</strong>.
        Si el alumno ya existía, entra como <strong>Incorporado</strong>. No queda activo: eso se resuelve con Reactivar alumnos.
        Un DNI repetido en la nómina, o un nombre que no coincide con la persona guardada, no se procesa.
    </p>
    <?php if (!$canEdit) : ?>
        <div class="alert alert-warning">Solo lectura: no tenés permisos para cargar alumnos.</div>
    <?php endif; ?>
    <form method="post" action="<?= e(url('/comisiones/' . rawurlencode((string) $comision['id']) . '/cargar-alumnos')) ?>">
        <?= $csrf->field() ?>
        <label class="form-label" for="data">Datos</label>
        <textarea class="form-control font-monospace"
                  name="data"
                  id="data"
                  rows="14"
                  placeholder="nombres&#9;apellidos&#9;cuil_dni&#9;fecha_nacimiento"
                  <?= $canEdit ? 'required' : 'disabled' ?>><?= e((string) ($rawData ?? '')) ?></textarea>
        <?php if ($canEdit) : ?>
            <button class="btn btn-primary mt-3" type="submit">Procesar</button>
        <?php endif; ?>
    </form>
</section>

<?php if ($report !== null) : ?>
    <section class="panel mt-3">
        <h2>Resultado</h2>
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Filas</div>
                    <div class="fs-4"><?= e((string) (int) $report['rows_total']) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Procesadas</div>
                    <div class="fs-4 text-success"><?= e((string) (int) $report['procesados']) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Errores</div>
                    <div class="fs-4 <?= ((int) $report['errores'] > 0) ? 'text-danger' : '' ?>"><?= e((string) (int) $report['errores']) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Ingresantes / incorporados / ya estaban</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['vinculos_ingresante']) ?>
                        / <?= e((string) (int) $report['vinculos_incorporado']) ?>
                        / <?= e((string) (int) $report['ya_en_comision']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (($report['log'] ?? []) === []) : ?>
            <div class="empty-state">Sin mensajes de detalle.</div>
        <?php else : ?>
            <div class="table-responsive" style="max-height: 480px; overflow: auto;">
                <table class="table table-sm table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th style="width: 6rem">Nivel</th>
                        <th>Mensaje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($report['log'] as $entry) : ?>
                        <?php $level = (string) ($entry['level'] ?? 'info'); ?>
                        <tr class="<?= e(import_log_row_class($level)) ?>">
                            <td class="<?= e($levelClass($level)) ?>"><?= e($level) ?></td>
                            <td class="<?= e($levelClass($level)) ?>"><?= e((string) ($entry['message'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
