<?php
$calendarioLabel = static fn (array $calendario): string => trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? ''));
$tomaEstado = static function (array $curso): string {
    return trim(implode(' / ', array_filter([
        $curso['toma_estado'] ?? '',
        $curso['estado_contralor'] ?? '',
    ])));
};
$estadoPlanillaBadge = static function (array $curso): array {
    $raw = trim((string) ($curso['estado_planilla'] ?? ''));
    $normalized = mb_strtolower($raw);
    if ($normalized === 'entregada') {
        return ['label' => 'Sí Entregada', 'class' => 'badge-planilla-entregada'];
    }

    $label = $raw !== '' ? $raw : 'No entregada';
    return ['label' => $label, 'class' => 'badge-planilla-no-entregada'];
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

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if ($cursos === []) : ?>
    <div class="empty-state">No se encontraron cursos para este calendario.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>Sede</th>
                <th>Comision</th>
                <th>Asignatura</th>
                <th>Docente</th>
                <th>Contacto</th>
                <th>Toma</th>
                <th>Estado planilla</th>
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
                $hsCat = trim(($curso['curso_horas_catedra'] ?? '') . '/' . ($curso['disposicion_horas_catedra'] ?? ''), '/');
                $planillaNumero = trim((string) ($curso['planilla_numero'] ?? ''));
                $estadoToma = $tomaEstado($curso);
                $planillaEstado = $estadoPlanillaBadge($curso);
                $telefono = preg_replace('/\D/', '', $curso['docente_telefono'] ?? '') ?? '';
                $nombres = explode(' ', trim($curso['docente_nombres'] ?? ''));
                $nombre = e($nombres[0] ?? '');
                $email = trim((string) ($curso['docente_email'] ?? ''));
                $emailAbc = trim((string) ($curso['docente_email_abc'] ?? ''));
                $resumenCopia = trim(preg_replace(
                    '/\s+/u',
                    ' ',
                    implode(' ', array_filter([
                        trim((string) ($curso['pfid'] ?? '')),
                        trim((string) ($curso['asignatura_nombre'] ?? '')),
                        trim((string) ($curso['docente_apellidos'] ?? '')),
                        trim((string) ($curso['docente_nombres'] ?? '')),
                    ], static fn (string $part): bool => $part !== '')),
                ) ?? '');
                $planLabel = trim(implode(' - ', array_filter([
                    trim((string) ($curso['plan_orientacion'] ?? '')),
                    trim((string) ($curso['plan_resolucion'] ?? '')),
                ], static fn (string $part): bool => $part !== '')));
                ?>
                <tr>
                    <td>
                        <div><?= e($curso['sede_nombre'] ?? '') ?></div>
                        <?php if ($planLabel !== '') : ?>
                            <div class="small text-secondary"><?= e($planLabel) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div><?= e($curso['pfid'] ?? '') ?></div>
                        <?php if (($curso['tramo_label'] ?? '') !== '') : ?>
                            <div class="small text-secondary"><?= e($curso['tramo_label']) ?></div>
                        <?php endif; ?>
                        <?php if ($hsCat !== '') : ?>
                            <div class="small text-secondary"><?= e($hsCat) ?> hs</div>
                        <?php endif; ?>
                    </td>
                    <td><?= e($asignatura) ?></td>
                    <td>
                        <?php if ($docente !== '' && ($curso['docente_id'] ?? '') !== '') : ?>
                            <a href="<?= e(url('/personas/' . rawurlencode((string) $curso['docente_id']) . '/docente')) ?>"><?= e($docente) ?></a>
                        <?php else : ?>
                            <span class="text-secondary">Sin docente</span>
                        <?php endif; ?>
                    </td>
                    <td class="small">
                        <?php if ($email !== '') : ?>
                            <div><?= e($email) ?></div>
                        <?php endif; ?>
                        <?php if ($emailAbc !== '') : ?>
                            <div class="text-secondary"><?= e($emailAbc) ?></div>
                        <?php endif; ?>
                        <?php if ($telefono !== '') : ?>
                            <div>
                                <a href="https://web.whatsapp.com/send/?phone=<?= e($telefono) ?>&text=Hola <?= $nombre ?> " target="_blank" rel="noopener"><?= e($telefono) ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($email === '' && $emailAbc === '' && $telefono === '') : ?>
                            <span class="text-secondary">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div><?= e($planillaNumero !== '' ? $planillaNumero : '-') ?></div>
                        <div class="small text-secondary"><?= e(trim((string) ($curso['fecha_toma'] ?? '')) !== '' ? $curso['fecha_toma'] : 'Sin fecha') ?></div>
                        <?php if ($estadoToma !== '') : ?>
                            <div class="small text-secondary"><?= e($estadoToma) ?></div>
                        <?php else : ?>
                            <div class="small text-secondary">Sin estado</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <span class="badge-planilla <?= e($planillaEstado['class']) ?>"><?= e($planillaEstado['label']) ?></span>
                        </div>
                        <div class="small text-secondary mt-1">
                            Aprobados: <?= e($curso['cantidad_aprobados'] ?? 0) ?>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <button
                            class="btn btn-sm btn-outline-dark"
                            type="button"
                            data-copy-text="<?= e($resumenCopia) ?>"
                            title="Copiar resumen: pfid asignatura apellidos nombres"
                            <?= $resumenCopia === '' ? 'disabled' : '' ?>
                        >CR</button>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/comisiones/' . rawurlencode((string) $curso['comision_id']) . '/alumnos')) ?>">
                            Ver alumnos
                        </a>
                        <a class="btn btn-sm btn-outline-success" href="<?= e(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla')) ?>">
                            Planilla
                        </a>
                        <?php if (($curso['toma_id'] ?? '') !== '' && $auth->canEdit()) : ?>
                            <form class="d-inline" method="post" action="<?= e(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla/entregada')) ?>"
                                  onsubmit="return confirm('¿Marcar la planilla como entregada?');">
                                <?= $csrf->field() ?>
                                <input type="hidden" name="return" value="cursos">
                                <button class="btn btn-sm btn-outline-secondary" type="submit" title="Marcar planilla entregada">
                                    Entregada
                                </button>
                            </form>
                        <?php endif; ?>
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
