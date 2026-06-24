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
$cuilDni = static function (array $alumno): string {
    $cuil = trim((string) ($alumno['cuil'] ?? ''));
    $dni = trim((string) ($alumno['numero_documento'] ?? ''));
    return $cuil !== '' && $dni !== '' && str_contains($cuil, $dni) ? $cuil : $dni;
};
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
                $fechaNacimiento = !empty($alumno['fecha_nacimiento'])
                    ? date('d/m/Y', strtotime((string) $alumno['fecha_nacimiento']))
                    : '';
                ?>
                <tr>
                    <td>
                        <strong><?= e(trim(($alumno['apellidos'] ?? '') . ', ' . ($alumno['nombres'] ?? ''), ' ,')) ?></strong>
                    </td>
                    <td><?= e($cuilDni($alumno)) ?></td>
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
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/personas/' . rawurlencode((string) $alumno['persona_id']) . '/alumno')) ?>">
                            Ver detalle
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="small text-secondary">Las calificaciones aprobadas se agrupan por año y semestre desde el tramo de ingreso del alumno.</p>
<?php endif; ?>
