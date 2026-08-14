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
        <h1 class="mt-2">Procesar comisiones PF</h1>
        <p class="text-secondary mb-0">
            Importá el informe global de ProgramaFines (copiar y pegar): actualiza horarios de cursos y crea tomas pendientes.
        </p>
    </div>
    <div>
        <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones')) ?>">Volver a comisiones</a>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Datos del informe global</h2>
    <ul class="text-secondary">
        <li>Copiá y pegá el texto del informe global de ProgramaFines.</li>
        <li>Se procesan líneas que contienen un día de la semana (Lunes…Viernes) con formato
            <code>{pfid}/{codigo} … Lunes …</code>.</li>
        <li>Solo se actualizan comisiones del <strong>calendario</strong> seleccionado (por PFID).</li>
        <li>Tras cada curso se espera la línea del docente con CUIL (<code>XX-XXXXXXXX-X</code>) o <code>*</code> si no hay designado.</li>
        <li>Si el docente no existe en el sistema, hay que cargarlo antes (p. ej. con Docentes PF).</li>
        <li>Si no hay toma Aprobada/Pendiente del curso, se crea una con estado <strong>Pendiente</strong>, movimiento <strong>AI</strong> y contralor <strong>Pasar</strong>.</li>
    </ul>

    <?php if (!$canEdit) : ?>
        <div class="alert alert-warning">Solo lectura: no tenés permisos para procesar importaciones.</div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/comisiones/procesar-pf')) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="calendario">Calendario</label>
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
                <div class="form-text">Solo se tocan comisiones con PFID de este calendario.</div>
            </div>
            <div class="col-12">
                <label class="form-label" for="data">Datos</label>
                <textarea class="form-control font-monospace"
                          name="data"
                          id="data"
                          rows="16"
                          placeholder="Pegá aquí el informe global..."
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
                    <div class="text-secondary small">Líneas</div>
                    <div class="fs-4"><?= e((string) (int) $report['lines_total']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Cursos procesados</div>
                    <div class="fs-4 text-success"><?= e((string) (int) $report['cursos_procesados']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Horarios actualizados</div>
                    <div class="fs-4"><?= e((string) (int) $report['horarios_actualizados']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">CUILs actualizados</div>
                    <div class="fs-4"><?= e((string) (int) $report['cuils_actualizados']) ?></div>
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
                    <div class="text-secondary small">Tomas OK / conflicto</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['tomas_ok']) ?> /
                        <?= e((string) (int) $report['tomas_conflicto']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Sin designar / curso no hallado</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['docentes_sin_designar']) ?> /
                        <?= e((string) (int) $report['cursos_no_encontrados']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Omitidas / errores</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['comisiones_omitidas']) ?> /
                        <span class="<?= ((int) $report['errores'] > 0) ? 'text-danger' : '' ?>">
                            <?= e((string) (int) $report['errores']) ?>
                        </span>
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
                        <tr>
                            <td class="<?= e($levelClass((string) ($entry['level'] ?? 'info'))) ?>">
                                <?= e((string) ($entry['level'] ?? 'info')) ?>
                            </td>
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
