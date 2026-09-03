<?php
$isNew = !empty($isNew);
$canEdit = $auth->canEdit();
$planillaId = (string) ($planilla['id'] ?? '');
$formAction = $isNew
    ? url('/planillas-docente')
    : url('/planillas-docente/' . rawurlencode($planillaId));

$dateValue = static function (mixed $value): string {
    $raw = trim((string) ($value ?? ''));
    if ($raw === '') {
        return '';
    }
    $date = date_create($raw);

    return $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $raw;
};

$label = trim((string) ($planilla['label'] ?? ''));
if ($label === '' && !$isNew) {
    $label = trim((string) ($planilla['numero'] ?? ''));
}
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/planillas-docente')) ?>">&larr; Volver a planillas docente</a>
        <h1 class="mt-2"><?= $isNew ? 'Nueva planilla docente' : 'Editar planilla docente' ?></h1>
        <p class="text-secondary mb-0">
            <?= e($isNew
                ? 'Completá los datos de la planilla para contralor.'
                : ($label !== '' ? $label : 'Planilla docente')) ?>
        </p>
    </div>
    <?php if (!$isNew && (int) ($planilla['tomas_count'] ?? 0) > 0) : ?>
        <div>
            <a class="btn btn-outline-secondary"
               href="<?= e(url('/informes/contralor?planilla_docente=' . rawurlencode($planillaId) . '&consultar=1')) ?>">
                Ver tomas (<?= e((string) (int) $planilla['tomas_count']) ?>)
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<?php if (!$canEdit) : ?>
    <div class="alert alert-warning">Solo lectura: no tenés permisos para modificar planillas docente.</div>
<?php endif; ?>

<section class="panel">
    <h2>Datos de la planilla</h2>
    <form method="post" action="<?= e($formAction) ?>">
        <?= $csrf->field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="numero">Número / nombre <span class="text-danger">*</span></label>
                <input class="form-control"
                       type="text"
                       name="numero"
                       id="numero"
                       maxlength="255"
                       value="<?= e((string) ($planilla['numero'] ?? '')) ?>"
                       placeholder="Ej: Fines y PCI Abril 2026"
                       <?= $canEdit ? 'required' : 'disabled' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="fecha_contralor">Fecha contralor</label>
                <input class="form-control"
                       type="date"
                       name="fecha_contralor"
                       id="fecha_contralor"
                       value="<?= e($dateValue($planilla['fecha_contralor'] ?? null)) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="fecha_consejo">Fecha consejo</label>
                <input class="form-control"
                       type="date"
                       name="fecha_consejo"
                       id="fecha_consejo"
                       value="<?= e($dateValue($planilla['fecha_consejo'] ?? null)) ?>"
                       <?= $canEdit ? '' : 'disabled' ?>>
            </div>
            <?php if (!$isNew) : ?>
                <div class="col-md-6">
                    <label class="form-label">Insertado</label>
                    <input class="form-control"
                           type="text"
                           value="<?= e(trim((string) ($planilla['insertado'] ?? '')) ?: '—') ?>"
                           disabled>
                </div>
            <?php endif; ?>
            <div class="col-12">
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea class="form-control"
                          name="observaciones"
                          id="observaciones"
                          rows="3"
                          <?= $canEdit ? '' : 'disabled' ?>><?= e((string) ($planilla['observaciones'] ?? '')) ?></textarea>
            </div>
        </div>

        <?php if ($canEdit) : ?>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <button class="btn btn-primary" type="submit">
                    <?= $isNew ? 'Crear planilla' : 'Guardar cambios' ?>
                </button>
                <a class="btn btn-outline-secondary" href="<?= e(url('/planillas-docente')) ?>">Cancelar</a>
            </div>
        <?php else : ?>
            <div class="mt-4">
                <a class="btn btn-outline-secondary" href="<?= e(url('/planillas-docente')) ?>">Volver</a>
            </div>
        <?php endif; ?>
    </form>
</section>
