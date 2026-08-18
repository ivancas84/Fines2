<?php
$defaults = form_values($defaults, [
    'nombres',
    'apellidos',
    'numero_documento',
    'cuil',
    'fecha_nacimiento',
    'telefono',
    'descripcion_domicilio',
    'email',
    'email_abc',
    'emails',
    'sede',
    'domicilio_sede',
    'pfid',
    'horario',
    'fecha_toma',
    'fecha_fin',
    'asignatura',
    'horas_catedra',
    'tramo',
    'resolucion',
    'contenido_html',
    'incluir_firmas',
    'enviar_email',
]);
if (trim((string) ($defaults['emails'] ?? '')) === '') {
    $defaults['emails'] = implode(', ', array_filter([
        trim((string) ($defaults['email_abc'] ?? '')),
        trim((string) ($defaults['email'] ?? '')),
    ]));
}
?>
<div class="page-header">
    <div>
        <h1>Toma de posesión</h1>
        <p class="text-secondary mb-0">
            Revisá los datos, generá el PDF y, si querés, enviá el email a uno o más destinatarios.
        </p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/constancias')) ?>">Volver</a>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit js-rich-editor-form" method="post" action="<?= e(url('/constancias/toma-posesion')) ?>">
        <?= $csrf->field() ?>

        <h2 class="h5">Datos del docente</h2>
        <div class="form-grid">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($defaults['nombres'] ?? '') ?>" required></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($defaults['apellidos'] ?? '') ?>"></label>
            <label class="form-label">DNI <input class="form-control" name="numero_documento" value="<?= e($defaults['numero_documento'] ?? '') ?>" required></label>
            <label class="form-label">CUIL <input class="form-control" name="cuil" value="<?= e($defaults['cuil'] ?? '') ?>"></label>
            <label class="form-label">Fecha de nacimiento <input class="form-control" name="fecha_nacimiento" value="<?= e($defaults['fecha_nacimiento'] ?? '') ?>"></label>
            <label class="form-label">Teléfono <input class="form-control" name="telefono" value="<?= e($defaults['telefono'] ?? '') ?>"></label>
            <label class="form-label">Email <input class="form-control" type="email" name="email" value="<?= e($defaults['email'] ?? '') ?>"></label>
            <label class="form-label">Email ABC <input class="form-control" type="email" name="email_abc" value="<?= e($defaults['email_abc'] ?? '') ?>"></label>
            <label class="form-label form-wide">Domicilio <input class="form-control" name="descripcion_domicilio" value="<?= e($defaults['descripcion_domicilio'] ?? '') ?>"></label>
        </div>

        <h2 class="h5 mt-4">Datos del cargo</h2>
        <div class="form-grid">
            <label class="form-label">Sede <input class="form-control" name="sede" value="<?= e($defaults['sede'] ?? '') ?>"></label>
            <label class="form-label">Comisión (PFID) <input class="form-control" name="pfid" value="<?= e($defaults['pfid'] ?? '') ?>"></label>
            <label class="form-label">Asignatura <input class="form-control" name="asignatura" value="<?= e($defaults['asignatura'] ?? '') ?>"></label>
            <label class="form-label">Hs cátedra <input class="form-control" name="horas_catedra" value="<?= e($defaults['horas_catedra'] ?? '') ?>"></label>
            <label class="form-label">Fecha toma <input class="form-control" name="fecha_toma" value="<?= e($defaults['fecha_toma'] ?? '') ?>"></label>
            <label class="form-label">Fecha fin <input class="form-control" name="fecha_fin" value="<?= e($defaults['fecha_fin'] ?? '') ?>"></label>
            <label class="form-label">Tramo <input class="form-control" name="tramo" value="<?= e($defaults['tramo'] ?? '') ?>"></label>
            <label class="form-label">Resolución <input class="form-control" name="resolucion" value="<?= e($defaults['resolucion'] ?? '') ?>"></label>
            <label class="form-label form-wide">Domicilio sede <input class="form-control" name="domicilio_sede" value="<?= e($defaults['domicilio_sede'] ?? '') ?>"></label>
            <label class="form-label form-wide">Horario <input class="form-control" name="horario" value="<?= e($defaults['horario'] ?? '') ?>"></label>
        </div>

        <div class="mt-4">
            <label class="form-label" for="contenido_html">Contenido de la toma</label>
            <p class="text-secondary small mb-2">
                Se incluye en el PDF. Podés editar las tablas de docente y cargo antes de generar.
            </p>
            <textarea class="form-control js-rich-table-editor" id="contenido_html" name="contenido_html" rows="10"><?= e($defaults['contenido_html'] ?? '') ?></textarea>
        </div>

        <h2 class="h5 mt-4">Envío</h2>
        <div class="form-grid">
            <label class="form-label form-wide">
                Emails (separados por coma)
                <input class="form-control" name="emails" value="<?= e($defaults['emails'] ?? '') ?>" placeholder="docente@abc.gob.ar, otro@correo.com">
            </label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="enviar_email" value="1" <?= checked($defaults['enviar_email'] ?? '1') ?>>
                <span class="form-check-label">Enviar email</span>
            </label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="incluir_firmas" value="1" <?= checked($defaults['incluir_firmas'] ?? '1') ?>>
                <span class="form-check-label">Incluir firmas</span>
            </label>
        </div>

        <button class="btn btn-primary mt-3" type="submit" data-submitting-text="Generando...">Generar toma</button>
    </form>
</section>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
