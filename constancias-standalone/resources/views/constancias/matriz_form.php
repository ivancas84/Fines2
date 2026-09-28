<?php
$defaults = form_values($defaults, [
    'nombres',
    'apellidos',
    'numero_documento',
    'fecha_nacimiento',
    'plan_estudios',
    'anio_semestre_ingreso',
    'libro_folio',
    'enlace_documentacion',
    'observaciones',
    'materias_html',
]);
?>
<div class="page-header">
    <div>
        <h1>Matriz</h1>
        <p class="text-secondary mb-0">Cuadro de asignaturas del plan, con nota en las aprobadas y E en equivalencias.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/constancias')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit js-rich-editor-form" method="post" action="<?= e(url('/constancias/matriz')) ?>">
        <?= $csrf->field() ?>
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($defaults['nombres'] ?? '') ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($defaults['apellidos'] ?? '') ?>" required></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($defaults['numero_documento'] ?? '') ?>" required></label>
            <label class="form-label">Fecha de nacimiento <input class="form-control" name="fecha_nacimiento" value="<?= e($defaults['fecha_nacimiento'] ?? '') ?>" placeholder="dd/mm/aaaa"></label>
            <label class="form-label">Plan de estudios <input class="form-control" name="plan_estudios" value="<?= e($defaults['plan_estudios'] ?? '') ?>"></label>
            <label class="form-label">A&ntilde;o / Semestre ingreso <input class="form-control" name="anio_semestre_ingreso" value="<?= e($defaults['anio_semestre_ingreso'] ?? '') ?>"></label>
            <label class="form-label">Libro y folio <input class="form-control" name="libro_folio" value="<?= e($defaults['libro_folio'] ?? '') ?>"></label>
            <label class="form-label form-wide">Ingreso con legajo <input class="form-control" name="enlace_documentacion" value="<?= e($defaults['enlace_documentacion'] ?? '') ?>"></label>
            <label class="form-label form-wide">Observaciones <textarea class="form-control" name="observaciones" rows="3"><?= e($defaults['observaciones'] ?? '') ?></textarea></label>
        </div>

        <p class="text-secondary mt-4 mb-3">
            Asignaturas del plan. Las equivalencias llevan E; las aprobadas, la nota; las desaprobadas quedan sin nota.
        </p>
        <label class="form-label" for="materias_html">Asignaturas</label>
        <textarea class="form-control js-rich-table-editor" id="materias_html" name="materias_html" rows="12"><?= e($defaults['materias_html'] ?? '') ?></textarea>

        <button class="btn btn-primary mt-3" type="submit" data-submitting-text="Generando...">Generar matriz</button>
    </form>
</section>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
