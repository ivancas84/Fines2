<?php
$isNew = !empty($isNew);
$canEdit = $auth->canEdit();
$calendarioId = (string) ($calendario['id'] ?? '');
$formAction = $isNew
    ? url('/calendarios')
    : url('/calendarios/' . rawurlencode($calendarioId));

$inicioValue = '';
$inicioRaw = trim((string) ($calendario['inicio'] ?? ''));
if ($inicioRaw !== '') {
    $inicioDate = date_create($inicioRaw);
    $inicioValue = $inicioDate instanceof DateTimeInterface ? $inicioDate->format('Y-m-d') : $inicioRaw;
}

$finValue = '';
$finRaw = trim((string) ($calendario['fin'] ?? ''));
if ($finRaw !== '') {
    $finDate = date_create($finRaw);
    $finValue = $finDate instanceof DateTimeInterface ? $finDate->format('Y-m-d') : $finRaw;
}

$label = trim((string) ($calendario['label'] ?? ''));
if ($label === '' && !$isNew) {
    $label = trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? ''));
}
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/calendarios')) ?>">&larr; Volver a calendarios</a>
        <h1 class="mt-2"><?= $isNew ? 'Nuevo calendario' : 'Editar calendario' ?></h1>
        <p class="text-secondary mb-0">
            <?= e($isNew
                ? 'Completá los datos del período lectivo.'
                : ($label !== '' ? $label : 'Calendario')) ?>
        </p>
    </div>
    <?php if (!$isNew && (int) ($calendario['comisiones_count'] ?? 0) > 0) : ?>
        <div>
            <a class="btn btn-outline-secondary"
               href="<?= e(url('/comisiones?calendario=' . rawurlencode($calendarioId))) ?>">
                Ver comisiones (<?= e((string) (int) $calendario['comisiones_count']) ?>)
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if (!$canEdit) : ?>
    <div class="alert alert-warning">Solo lectura: no tenés permisos para modificar calendarios.</div>
<?php endif; ?>

<section class="panel">
    <h2>Datos del calendario</h2>
    <form method="post" action="<?= e($formAction) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="anio">Año <span class="text-danger">*</span></label>
                <input class="form-control"
                       type="number"
                       name="anio"
                       id="anio"
                       min="2000"
                       max="2100"
                       step="1"
                       value="<?= e((string) ($calendario['anio'] ?? '')) ?>"
                       <?= $canEdit ? 'required' : 'disabled' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="semestre">Semestre <span class="text-danger">*</span></label>
                <select class="form-select" name="semestre" id="semestre" <?= $canEdit ? 'required' : 'disabled' ?>>
                    <option value="1" <?= selected($calendario['semestre'] ?? '', 1) ?>>1</option>
                    <option value="2" <?= selected($calendario['semestre'] ?? '', 2) ?>>2</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="descripcion">Descripción</label>
                <input class="form-control"
                       type="text"
                       name="descripcion"
                       id="descripcion"
                       maxlength="255"
                       value="<?= e((string) ($calendario['descripcion'] ?? '')) ?>"
                       placeholder="Ej: 1er semestre 2026"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="inicio">Fecha de inicio</label>
                <input class="form-control"
                       type="date"
                       name="inicio"
                       id="inicio"
                       value="<?= e($inicioValue) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="fin">Fecha de fin</label>
                <input class="form-control"
                       type="date"
                       name="fin"
                       id="fin"
                       value="<?= e($finValue) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <?php if (!$isNew) : ?>
                <div class="col-md-6">
                    <label class="form-label">Insertado</label>
                    <input class="form-control"
                           type="text"
                           value="<?= e(trim((string) ($calendario['insertado'] ?? '')) ?: '—') ?>"
                           disabled>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($canEdit) : ?>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <button class="btn btn-primary" type="submit">
                    <?= $isNew ? 'Crear calendario' : 'Guardar cambios' ?>
                </button>
                <a class="btn btn-outline-secondary" href="<?= e(url('/calendarios')) ?>">Cancelar</a>
            </div>
        <?php else : ?>
            <div class="mt-4">
                <a class="btn btn-outline-secondary" href="<?= e(url('/calendarios')) ?>">Volver</a>
            </div>
        <?php endif; ?>
    </form>
</section>
