<?php
$canEdit = $auth->canEdit();
$report = is_array($report ?? null) ? $report : null;
$levelClass = static function (string $level): string {
    return match ($level) {
        'success' => 'text-success',
        'warning' => 'text-warning-emphasis',
        'error' => 'text-danger',
        default => 'text-secondary',
    };
};
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/herramientas')) ?>">&larr; Volver a herramientas</a>
        <h1 class="mt-2">Procesar docentes PF</h1>
        <p class="text-secondary mb-0">
            Importá docentes desde el XLSX de ProgramaFines (copiar y pegar). Inserta o actualiza personas y crea tomas del CENS configurado.
        </p>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Datos de la planilla</h2>
    <ul class="text-secondary">
        <li>Descargá el XLSX de docentes de ProgramaFines y copiá las filas con encabezados.</li>
        <li>Columnas esperadas (flexibles): Nombre, Apellido, DNI, Dirección, Localidad, FechaNac, Celular, Email, Comisión, Materia, CENS.</li>
        <li>Se insertan o actualizan personas por DNI. Si el DNI ya existe y el nombre no es parecido, la fila falla.</li>
        <li>Para el CENS indicado (por defecto <strong>462</strong>) también se crean tomas pendientes si no hay toma activa en el curso de la materia.</li>
        <li>Se recomienda eliminar del pegado las últimas columnas de Dirección/Localidad del CENS si confunden el armado de columnas.</li>
    </ul>

    <?php if (!$canEdit) : ?>
        <div class="alert alert-warning">Solo lectura: no tenés permisos para procesar importaciones.</div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/docentes/procesar-pf')) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label" for="calendario">Calendario (para tomas)</label>
                <select class="form-select" name="calendario" id="calendario" <?= $canEdit ? 'required' : 'disabled' ?>>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($calendarios as $calendario) : ?>
                        <?php
                        $label = trim(implode(' ', array_filter([
                            trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '')),
                            (string) ($calendario['descripcion'] ?? ''),
                        ])));
                        ?>
                        <option value="<?= e((string) $calendario['id']) ?>" <?= selected($selectedCalendario, $calendario['id']) ?>>
                            <?= e($label !== '' ? $label : (string) $calendario['id']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Las comisiones se buscan por PFID dentro de este calendario.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="cens_tomas">CENS para tomas</label>
                <input class="form-control"
                       type="text"
                       name="cens_tomas"
                       id="cens_tomas"
                       value="<?= e((string) ($censTomas ?? '462')) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
                <div class="form-text">Solo esas filas generan/verifican tomas.</div>
            </div>
            <div class="col-12">
                <label class="form-label" for="data">Datos (TSV desde Excel)</label>
                <textarea class="form-control font-monospace"
                          name="data"
                          id="data"
                          rows="14"
                          placeholder="Pegá aquí las filas con encabezados..."
                          <?= $canEdit ? 'required' : 'disabled' ?>><?= e((string) ($rawData ?? '')) ?></textarea>
            </div>
            <?php if ($canEdit) : ?>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Procesar</button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</section>

<?php if ($report !== null) : ?>
    <section class="panel mt-3">
        <h2>Resultado</h2>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Filas</div>
                    <div class="fs-4"><?= e((string) (int) $report['rows_total']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Docentes insertados</div>
                    <div class="fs-4 text-success"><?= e((string) (int) $report['docentes_insertados']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Existentes / modificados</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['docentes_existentes']) ?> /
                        <?= e((string) (int) $report['docentes_modificados']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Sin designar (otros CENS)</div>
                    <div class="fs-4"><?= e((string) (int) $report['docentes_sin_designar']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Tomas creadas</div>
                    <div class="fs-4 text-success"><?= e((string) (int) $report['tomas_creadas']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Tomas ya existentes</div>
                    <div class="fs-5">
                        mismo: <?= e((string) (int) $report['tomas_existentes_mismo']) ?> /
                        otro: <?= e((string) (int) $report['tomas_existentes_otro']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Errores</div>
                    <div class="fs-4 <?= ((int) $report['errores'] > 0) ? 'text-danger' : '' ?>">
                        <?= e((string) (int) $report['errores']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (($report['log'] ?? []) === []) : ?>
            <div class="empty-state">Sin mensajes de detalle (solo se detallan filas del CENS de tomas y errores).</div>
        <?php else : ?>
            <div class="table-responsive" style="max-height: 480px; overflow: auto;">
                <table class="table table-sm table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th style="width: 5rem">Nivel</th>
                        <th style="width: 5rem">Fila</th>
                        <th style="width: 5rem">CENS</th>
                        <th>Mensaje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($report['log'] as $entry) : ?>
                        <tr>
                            <td class="<?= e($levelClass((string) ($entry['level'] ?? 'info'))) ?>">
                                <?= e((string) ($entry['level'] ?? 'info')) ?>
                            </td>
                            <td><?= e(isset($entry['row']) ? (string) $entry['row'] : '—') ?></td>
                            <td><?= e((string) ($entry['cens'] ?? '—')) ?></td>
                            <td class="<?= e($levelClass((string) ($entry['level'] ?? 'info'))) ?>">
                                <?= e((string) ($entry['message'] ?? '')) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
