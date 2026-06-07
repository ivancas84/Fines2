<?php
$defaults = form_values($defaults, [
    'nombres',
    'apellidos',
    'numero_documento',
    'anio',
    'modalidad',
    'orientacion',
    'resolucion',
    'fecha',
    'presentado',
    'observaciones',
    'incluir_firmas',
    'materias_aprobadas_html',
    'materias_desaprobadas_html',
]);
?>
<div class="page-header">
    <div>
        <h1>Constancia de pase</h1>
        <p class="text-secondary mb-0">Formulario con tablas editables para materias aprobadas y desaprobadas.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/constancias')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit js-rich-editor-form" method="post" action="<?= e(url('/constancias/pase')) ?>">
        <?= $csrf->field() ?>
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($defaults['nombres'] ?? '') ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($defaults['apellidos'] ?? '') ?>" required></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($defaults['numero_documento'] ?? '') ?>" required></label>
            <label class="form-label">A&ntilde;o <input class="form-control" name="anio" value="<?= e($defaults['anio'] ?? '') ?>" required></label>
            <label class="form-label">Modalidad <input class="form-control" name="modalidad" value="<?= e($defaults['modalidad'] ?? '') ?>" required></label>
            <label class="form-label">Orientaci&oacute;n <input class="form-control" name="orientacion" value="<?= e($defaults['orientacion'] ?? '') ?>" required></label>
            <label class="form-label">Resoluci&oacute;n <input class="form-control" name="resolucion" value="<?= e($defaults['resolucion'] ?? '') ?>" required></label>
            <label class="form-label">Fecha <input class="form-control" name="fecha" value="<?= e($defaults['fecha'] ?? '') ?>" required></label>
            <label class="form-label">Presentado a <input class="form-control" name="presentado" value="<?= e($defaults['presentado'] ?? '') ?>" required></label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="incluir_firmas" value="1" <?= checked($defaults['incluir_firmas'] ?? '1') ?>>
                <span class="form-check-label">Incluir firmas</span>
            </label>
        </div>

        <label class="form-label form-wide mt-3">Observaciones <textarea class="form-control" name="observaciones" rows="3"><?= e($defaults['observaciones'] ?? '') ?></textarea></label>

        <p class="text-secondary mt-4 mb-3">
            Indique las materias aprobadas y desaprobadas. Si lo desea, copie y pegue la informaci&oacute;n desde una hoja de c&aacute;lculo, como Excel, Google Sheets u otra similar.
        </p>

        <div class="rich-table-grid mt-3">
            <div>
                <label class="form-label" for="materias_aprobadas_html">Materias aprobadas</label>
                <textarea class="form-control js-rich-table-editor" id="materias_aprobadas_html" name="materias_aprobadas_html" rows="8"><?= e($defaults['materias_aprobadas_html'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="form-label" for="materias_desaprobadas_html">Materias desaprobadas / pendientes</label>
                <textarea class="form-control js-rich-table-editor" id="materias_desaprobadas_html" name="materias_desaprobadas_html" rows="8"><?= e($defaults['materias_desaprobadas_html'] ?? '') ?></textarea>
            </div>
        </div>

        <button class="btn btn-primary mt-3" type="submit" data-submitting-text="Generando...">Generar constancia</button>
    </form>
</section>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
