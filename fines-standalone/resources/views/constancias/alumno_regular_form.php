<?php
$v = static fn (array $row, string $key): string => (string) ($row[$key] ?? '');
$personaLabel = trim(implode(' ', array_filter([$v($persona, 'apellidos'), $v($persona, 'nombres')])));
$notas = $ultimaComision === null ? 'No se encontro una comision asociada al alumno.' : trim(implode(' | ', array_filter([
    'Sede: ' . ($ultimaComision['sede_label'] ?? ''),
    'Comision: ' . ($ultimaComision['pfid'] ?? ''),
    'Periodo: ' . ($ultimaComision['calendario_label'] ?? ''),
    'Tramo: ' . ($ultimaComision['tramo_label'] ?? ''),
])));
?>
<div class="page-header">
    <div>
        <h1>Constancia de alumno regular</h1>
        <p class="text-secondary mb-0"><?= e($personaLabel) ?> - DNI <?= e($v($persona, 'numero_documento')) ?></p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/personas/' . $persona['id'] . '/alumno')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form method="post" action="<?= e(url('/personas/' . $persona['id'] . '/alumno/constancias/alumno-regular')) ?>">
        <?= $csrf->field() ?>
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($defaults['nombres'] ?? '') ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($defaults['apellidos'] ?? '') ?>" required></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($defaults['numero_documento'] ?? '') ?>" required></label>
            <label class="form-label">Anio en curso <input class="form-control" name="anio" value="<?= e($defaults['anio'] ?? '') ?>" required></label>
            <label class="form-label">Orientacion <input class="form-control" name="orientacion" value="<?= e($defaults['orientacion'] ?? '') ?>" required></label>
            <label class="form-label">Resolucion <input class="form-control" name="resolucion" value="<?= e($defaults['resolucion'] ?? '') ?>" required></label>
            <label class="form-label">Fecha <input class="form-control" name="fecha" value="<?= e($defaults['fecha'] ?? '') ?>" required></label>
            <label class="form-label">Presentado a <input class="form-control" name="presentado" value="<?= e($defaults['presentado'] ?? '') ?>" required></label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="incluir_firmas" value="1" <?= checked($defaults['incluir_firmas'] ?? '1') ?>>
                <span class="form-check-label">Incluir firmas</span>
            </label>
            <label class="form-label form-wide">Observaciones <textarea class="form-control" name="observaciones" rows="3"><?= e($defaults['observaciones'] ?? '') ?></textarea></label>
            <label class="form-label form-wide">Notas <textarea class="form-control" rows="3" readonly><?= e($notas) ?></textarea></label>
        </div>
        <button class="btn btn-primary mt-3" type="submit">Generar constancia</button>
    </form>
</section>
