<?php
$canEdit = $auth->canEdit();
$report = is_array($report ?? null) ? $report : null;
$levelClass = static fn (string $level): string => import_log_level_class($level);
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/herramientas')) ?>">&larr; Volver a herramientas</a>
        <h1 class="mt-2">Procesar comisiones PCI</h1>
        <p class="text-secondary mb-0">
            Importá el Informe Global por Comisión PCI (copiar y pegar): actualiza horarios de Áreas A–E y crea tomas pendientes.
        </p>
    </div>
    <div>
        <a class="btn btn-outline-secondary" href="<?= e(url('/comisiones')) ?>">Volver a comisiones</a>
    </div>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Datos del informe PCI</h2>
    <ul class="text-secondary">
        <li>Copiá y pegá el texto del <strong>Informe Global por Comisión PCI</strong>.</li>
        <li>Cada comisión empieza con <code>Comisión: {pfid}</code> y cada materia con <code>Área A</code> … <code>Área E</code>.</li>
        <li>Se ignoran encabezados repetidos por salto de página del PDF (<em>La Plata</em>, período, cargos vigentes).</li>
        <li>Solo se actualizan comisiones del <strong>calendario</strong> seleccionado (por PFID) que ya tengan cursos AREA A–E.</li>
        <li>El docente se busca por DNI. Si no existe, hay que cargarlo antes en
            <a href="<?= e(url('/personas/nueva')) ?>">Nueva persona</a>.</li>
        <li>Si el nombre de la persona o el docente de la toma difieren, <strong>no se actualizan</strong> y el log lo marca para verificar.</li>
        <li><code>Docente: Sin designar</code> no crea toma. Si no hay toma Aprobada/Pendiente, se crea una con estado <strong>Pendiente</strong>, movimiento <strong>AI</strong> y contralor <strong>Pasar</strong>.</li>
    </ul>

    <?php if (!$canEdit) : ?>
        <div class="alert alert-warning">Solo lectura: no tenés permisos para procesar importaciones.</div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/comisiones/procesar-pci')) ?>">
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
                <div class="form-text">Solo se tocan comisiones PCI con PFID de este calendario.</div>
            </div>
            <div class="col-12">
                <label class="form-label" for="data">Datos</label>
                <textarea class="form-control font-monospace"
                          name="data"
                          id="data"
                          rows="16"
                          placeholder="Pegá aquí el informe global PCI..."
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
                    <div class="text-secondary small">Comisiones / áreas</div>
                    <div class="fs-4">
                        <?= e((string) (int) $report['comisiones_detectadas']) ?> /
                        <?= e((string) (int) $report['areas_detectadas']) ?>
                    </div>
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
                    <div class="text-secondary small">Tomas creadas</div>
                    <div class="fs-4 text-success"><?= e((string) (int) $report['tomas_creadas']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Personas a verificar</div>
                    <div class="fs-4 <?= ((int) ($report['personas_diferentes'] ?? 0) > 0) ? 'import-log-conflict-text' : '' ?>">
                        <?= e((string) (int) ($report['personas_diferentes'] ?? 0)) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Tomas OK / conflicto</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['tomas_ok']) ?> /
                        <span class="<?= ((int) $report['tomas_conflicto'] > 0) ? 'import-log-conflict-text' : '' ?>">
                            <?= e((string) (int) $report['tomas_conflicto']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Sin designar / DNI inexistente</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['docentes_sin_designar']) ?> /
                        <?= e((string) (int) ($report['docentes_inexistentes'] ?? 0)) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary small">Omitidas / curso no hallado</div>
                    <div class="fs-5">
                        <?= e((string) (int) $report['comisiones_omitidas']) ?> /
                        <?= e((string) (int) $report['cursos_no_encontrados']) ?>
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
                            <td class="<?= e($levelClass($level)) ?>">
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
