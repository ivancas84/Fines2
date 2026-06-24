<?php
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
$sexoLabel = static fn (mixed $sexo): string => match ((int) $sexo) {
    1 => 'Masculino',
    2 => 'Femenino',
    3 => 'No binario',
    default => '',
};
$tramoIngreso = static function (array $alumno): string {
    $anio = trim((string) ($alumno['anio_ingreso'] ?? ''));
    if ($anio === '') {
        return '';
    }

    $semestre = trim((string) ($alumno['semestre_ingreso'] ?? ''));
    return $anio . '° ' . ($semestre !== '' ? $semestre : '1') . 'C';
};
$normalizeDni = static fn (mixed $dni): string => preg_replace('/\D+/', '', (string) $dni) ?? '';
$programaFinesUrl = static function (mixed $path): string {
    $path = html_entity_decode(trim((string) $path), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (preg_match('#^https?://#i', $path) === 1) {
        return $path;
    }
    if (str_starts_with($path, '/')) {
        return 'https://www.programafines.ar' . $path;
    }
    return 'https://www.programafines.ar/inicial/' . ltrim($path, '/');
};
$pfBoth = array_fill_keys($programaFines['both'] ?? [], true);
$pfLocalOnly = array_fill_keys($programaFines['local_only'] ?? [], true);
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/comisiones')) ?>">&larr; Volver a comisiones</a>
        <h1 class="mt-2">Alumnos de la comision</h1>
        <p class="text-secondary mb-0"><?= e($comisionLabel ?: 'Comision') ?></p>
        <?php if ($planLabel !== '') : ?>
            <p class="small text-secondary mb-0"><?= e($planLabel) ?></p>
        <?php endif; ?>
    </div>
    <span class="badge text-bg-primary fs-6"><?= e((string) count($alumnos)) ?> alumnos</span>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if (!$programaFines['connected']) : ?>
    <div class="alert alert-info">
        <a href="<?= e(url('/programafines')) ?>">Conectá ProgramaFines</a> para comparar esta comisión con la lista remota.
    </div>
<?php elseif (($comision['pfid'] ?? '') === '') : ?>
    <div class="alert alert-warning">Esta comisión no tiene PFID y no puede vincularse con ProgramaFines.</div>
<?php elseif (!empty($programaFines['error'])) : ?>
    <div class="alert alert-danger">
        No se pudo consultar ProgramaFines: <?= e($programaFines['error']) ?>
        <a class="alert-link" href="<?= e(url('/programafines')) ?>">Revisar conexión</a>
    </div>
<?php else : ?>
    <section class="panel">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2>Comparación con ProgramaFines</h2>
                <p class="text-secondary mb-0">PFID <?= e($comision['pfid']) ?> · período remoto <?= e((string) $programaFines['periodo']) ?></p>
            </div>
            <?php if ($auth->canEdit()) : ?>
                <form method="post" action="<?= e(url('/comisiones/' . $comision['id'] . '/programafines/sincronizar')) ?>" onsubmit="return confirm('¿Enviar y actualizar todos los alumnos locales en ProgramaFines?')">
                    <?= $csrf->field() ?>
                    <button class="btn btn-primary" type="submit">Sincronizar todos los locales</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-4">
                <div class="pf-summary pf-summary-warning">
                    <strong><?= e((string) count($programaFines['local_only'])) ?></strong>
                    <span>solo en la base local</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pf-summary pf-summary-danger">
                    <strong><?= e((string) count($programaFines['remote_only'])) ?></strong>
                    <span>solo en ProgramaFines</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pf-summary pf-summary-success">
                    <strong><?= e((string) count($programaFines['both'])) ?></strong>
                    <span>en ambas listas</span>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($alumnos === []) : ?>
    <div class="empty-state">No se encontraron alumnos para esta comision.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>Alumno</th>
                <th>CUIL / DNI</th>
                <th>Contacto</th>
                <th>Nacimiento</th>
                <th>Sexo</th>
                <th>Ingreso</th>
                <th>Aprobadas</th>
                <th>Estado</th>
                <?php if ($programaFines['connected'] && empty($programaFines['error']) && ($comision['pfid'] ?? '') !== '') : ?>
                    <th>ProgramaFines</th>
                <?php endif; ?>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($alumnos as $alumno) : ?>
                <?php
                $telefono = trim(implode(' ', array_filter([
                    $alumno['codigo_area'] ?? '',
                    $alumno['telefono'] ?? '',
                ])));
                $fechaNacimiento = persona_fecha_nacimiento($alumno);
                $dni = $normalizeDni($alumno['numero_documento'] ?? '');
                ?>
                <tr>
                    <td>
                        <strong><?= e(trim(($alumno['apellidos'] ?? '') . ', ' . ($alumno['nombres'] ?? ''), ' ,')) ?></strong>
                    </td>
                    <td><?= e(persona_cuil($alumno)) ?></td>
                    <td>
                        <?php if ($telefono !== '') : ?><div><?= e($telefono) ?></div><?php endif; ?>
                        <?php if (($alumno['email'] ?? '') !== '') : ?><div class="small"><?= e($alumno['email']) ?></div><?php endif; ?>
                    </td>
                    <td><?= e($fechaNacimiento) ?></td>
                    <td><?= e($sexoLabel($alumno['sexo'] ?? null)) ?></td>
                    <td><?= e($tramoIngreso($alumno)) ?></td>
                    <td>
                        <?php if (($alumno['aprobadas_por_tramo'] ?? []) === [] && (int) ($alumno['aprobadas_otros_planes'] ?? 0) === 0) : ?>
                            <span class="text-secondary">Sin calificaciones aprobadas</span>
                        <?php else : ?>
                            <?php foreach ($alumno['aprobadas_por_tramo'] as $aprobadas) : ?>
                                <div><strong><?= e($aprobadas['label']) ?>:</strong> <?= e((string) $aprobadas['cantidad']) ?></div>
                            <?php endforeach; ?>
                            <?php if ((int) ($alumno['aprobadas_otros_planes'] ?? 0) > 0) : ?>
                                <div class="text-primary">+ <?= e((string) $alumno['aprobadas_otros_planes']) ?> de otro plan</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ((int) ($alumno['activo'] ?? 0) === 1) : ?>
                            <span class="badge text-bg-success">Activo</span>
                        <?php else : ?>
                            <span class="badge text-bg-secondary"><?= e($alumno['estado'] ?? 'Inactivo') ?></span>
                        <?php endif; ?>
                    </td>
                    <?php if ($programaFines['connected'] && empty($programaFines['error']) && ($comision['pfid'] ?? '') !== '') : ?>
                        <td>
                            <?php if (isset($pfBoth[$dni])) : ?>
                                <span class="badge text-bg-success">En ambas</span>
                            <?php elseif (isset($pfLocalOnly[$dni])) : ?>
                                <span class="badge text-bg-warning">Solo local</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/personas/' . rawurlencode((string) $alumno['persona_id']) . '/alumno')) ?>">
                                Ver detalle
                            </a>
                            <?php if ($auth->canEdit() && $programaFines['connected'] && empty($programaFines['error']) && ($comision['pfid'] ?? '') !== '') : ?>
                                <form method="post" action="<?= e(url('/comisiones/' . $comision['id'] . '/programafines/enviar')) ?>" onsubmit="return confirm('¿Enviar o actualizar este alumno en ProgramaFines?')">
                                    <?= $csrf->field() ?>
                                    <input type="hidden" name="alumno_comision_id" value="<?= e($alumno['alumno_comision_id']) ?>">
                                    <button class="btn btn-sm btn-outline-success" type="submit">Enviar a PF</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="small text-secondary">Las calificaciones aprobadas se agrupan por año y semestre desde el tramo de ingreso del alumno.</p>
<?php endif; ?>

<?php if ($programaFines['connected'] && empty($programaFines['error']) && ($comision['pfid'] ?? '') !== '') : ?>
    <section class="panel">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2>Alumnos en ProgramaFines</h2>
                <p class="text-secondary mb-0"><?= e((string) count($programaFines['students'])) ?> alumnos informados por el sitio externo.</p>
            </div>
        </div>

        <?php if ($programaFines['students'] === []) : ?>
            <div class="empty-state mt-3">ProgramaFines no devolvió alumnos para esta comisión y período.</div>
        <?php else : ?>
            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle app-table">
                    <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>DNI</th>
                        <th>Nacimiento</th>
                        <th>Contacto</th>
                        <th>Comparación</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($programaFines['students'] as $remote) : ?>
                        <?php $remoteDni = $normalizeDni($remote['numero_documento'] ?? ''); ?>
                        <tr>
                            <td><strong><?= e($remote['nombre'] ?? '') ?></strong></td>
                            <td><?= e($remoteDni) ?></td>
                            <td><?= e($remote['fecha_nacimiento'] ?? '') ?></td>
                            <td>
                                <?php if (!empty($remote['telefono'])) : ?><div><?= e($remote['telefono']) ?></div><?php endif; ?>
                                <?php if (!empty($remote['email'])) : ?><div class="small"><?= e($remote['email']) ?></div><?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($pfBoth[$remoteDni])) : ?>
                                    <span class="badge text-bg-success">En ambas</span>
                                <?php else : ?>
                                    <span class="badge text-bg-danger">Solo en PF</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php if ($auth->canEdit() && !isset($pfBoth[$remoteDni])) : ?>
                                        <form method="post" action="<?= e(url('/comisiones/' . $comision['id'] . '/programafines/importar')) ?>" onsubmit="return confirm('¿Importar este alumno a la base local y agregarlo a la comisión?')">
                                            <?= $csrf->field() ?>
                                            <input type="hidden" name="dni" value="<?= e($remoteDni) ?>">
                                            <button class="btn btn-sm btn-outline-primary" type="submit">Importar local</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (!empty($remote['historial'])) : ?>
                                        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" href="<?= e($programaFinesUrl($remote['historial'])) ?>">Historial</a>
                                    <?php endif; ?>
                                    <?php if ($auth->canEdit()) : ?>
                                        <form method="post" action="<?= e(url('/comisiones/' . $comision['id'] . '/programafines/quitar')) ?>" onsubmit="return confirm('¿Quitar este alumno de la comisión en ProgramaFines?')">
                                            <?= $csrf->field() ?>
                                            <input type="hidden" name="dni" value="<?= e($remoteDni) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Quitar de PF</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
