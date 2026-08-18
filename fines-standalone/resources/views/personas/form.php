<?php
$canEdit = $auth->canEdit();
$v = static fn (array $row, string $key): string => (string) ($row[$key] ?? '');
$funcion = in_array((string) ($funcion ?? 'persona'), ['persona', 'alumno', 'docente'], true)
    ? (string) $funcion
    : 'persona';
$existing = is_array($existing ?? null) ? $existing : null;
$existingLabel = $existing === null
    ? ''
    : trim(implode(', ', array_filter([
        trim((string) ($existing['apellidos'] ?? '')),
        trim((string) ($existing['nombres'] ?? '')),
    ])));
?>
<div class="page-header">
    <div>
        <a class="small" href="<?= e(url('/personas')) ?>">&larr; Volver a personas</a>
        <h1 class="mt-2">Nueva persona</h1>
        <p class="text-secondary mb-0">
            Primero se cargan los datos de identidad. El rol de alumno o docente se puede completar ahora o más adelante.
        </p>
    </div>
</div>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger">
        <?= e((string) $error) ?>
        <?php if ($existing !== null) : ?>
            <?php $existingId = (string) ($existing['id'] ?? ''); ?>
            <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
                <?php if ($existingLabel !== '') : ?>
                    <span><?= e($existingLabel) ?> · DNI <?= e((string) ($existing['numero_documento'] ?? '')) ?></span>
                <?php endif; ?>
                <?php if ($existingId !== '') : ?>
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/personas/' . $existingId . '/alumno')) ?>">Ficha alumno</a>
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/personas/' . $existingId . '/docente')) ?>">Ficha docente</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (!$canEdit) : ?>
    <div class="alert alert-warning">Solo lectura: no tenés permisos para crear personas.</div>
<?php endif; ?>

<form method="post" action="<?= e(url('/personas')) ?>" id="form-nueva-persona">
    <?= $csrf->field() ?>

    <section class="panel">
        <h2>Datos principales</h2>
        <p class="text-secondary">
            Estos datos alcanzan para después asignar la persona como designación de una sede, docente o alumno.
        </p>
        <div class="form-grid form-grid-persona">
            <label class="form-label">Nombres <input class="form-control" name="nombres" value="<?= e($v($persona, 'nombres')) ?>" <?= $canEdit ? 'required' : 'disabled' ?>></label>
            <label class="form-label">Apellidos <input class="form-control" name="apellidos" value="<?= e($v($persona, 'apellidos')) ?>" <?= $canEdit ? 'required' : 'disabled' ?>></label>
            <label class="form-label">
                Sexo
                <select class="form-select" name="sexo" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">Seleccione...</option>
                    <option value="1" <?= selected($v($persona, 'sexo'), '1') ?>>Masculino</option>
                    <option value="2" <?= selected($v($persona, 'sexo'), '2') ?>>Femenino</option>
                    <option value="3" <?= selected($v($persona, 'sexo'), '3') ?>>No binario</option>
                </select>
            </label>
            <div class="form-composite form-cuil-grid">
                <label class="form-label">CUIL1 <input class="form-control" inputmode="numeric" minlength="2" maxlength="2" pattern="[0-9]{2}" name="cuil1" value="<?= e($v($persona, 'cuil1')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
                <label class="form-label">DNI <input class="form-control" inputmode="numeric" minlength="8" maxlength="8" pattern="[0-9]{8}" name="numero_documento" value="<?= e($v($persona, 'numero_documento')) ?>" <?= $canEdit ? 'required' : 'disabled' ?>></label>
                <label class="form-label">CUIL2 <input class="form-control" inputmode="numeric" minlength="1" maxlength="1" pattern="[0-9]" name="cuil2" value="<?= e($v($persona, 'cuil2')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            </div>
            <div class="form-composite form-date-grid">
                <label class="form-label">Día nac. <input class="form-control" type="number" min="1" max="31" name="dia_nacimiento" value="<?= e($v($persona, 'dia_nacimiento')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
                <label class="form-label">Mes nac. <input class="form-control" type="number" min="1" max="12" name="mes_nacimiento" value="<?= e($v($persona, 'mes_nacimiento')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
                <label class="form-label">Año nac. <input class="form-control" type="number" min="1900" max="<?= e(date('Y')) ?>" name="anio_nacimiento" value="<?= e($v($persona, 'anio_nacimiento')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            </div>
            <div class="form-composite form-phone-grid">
                <label class="form-label">Codigo area <input class="form-control" name="codigo_area" value="<?= e($v($persona, 'codigo_area')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
                <label class="form-label">Telefono <input class="form-control" name="telefono" value="<?= e($v($persona, 'telefono')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            </div>
            <label class="form-label">Email <input class="form-control" type="email" name="email" value="<?= e($v($persona, 'email')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Email ABC <input class="form-control" type="email" name="email_abc" value="<?= e($v($persona, 'email_abc')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Lugar nacimiento <input class="form-control" name="lugar_nacimiento" value="<?= e($v($persona, 'lugar_nacimiento')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Nacionalidad <input class="form-control" name="nacionalidad" value="<?= e($v($persona, 'nacionalidad')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Domicilio <input class="form-control" name="descripcion_domicilio" value="<?= e($v($persona, 'descripcion_domicilio')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Departamento <input class="form-control" name="departamento" value="<?= e($v($persona, 'departamento')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Localidad <input class="form-control" name="localidad" value="<?= e($v($persona, 'localidad')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-label">Partido <input class="form-control" name="partido" value="<?= e($v($persona, 'partido')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
        </div>
    </section>

    <section class="panel">
        <h2>Función</h2>
        <p class="text-secondary">
            Una persona no es alumno ni docente por sí misma: el alumno tiene ficha propia, el docente se define por las tomas y una designación se asigna después en la sede.
        </p>
        <div class="role-options">
            <label class="role-option">
                <input type="radio" name="funcion" value="persona" <?= checked($funcion, 'persona') ?> <?= $canEdit ? '' : 'disabled' ?>>
                <span>
                    <strong>Solo persona</strong>
                    <span>Datos principales. Sirve para designarla en una sede o completar el rol más adelante.</span>
                </span>
            </label>
            <label class="role-option">
                <input type="radio" name="funcion" value="alumno" <?= checked($funcion, 'alumno') ?> <?= $canEdit ? '' : 'disabled' ?>>
                <span>
                    <strong>Alumno</strong>
                    <span>Crea también la ficha de alumno (plan e ingreso). Las comisiones se cargan después.</span>
                </span>
            </label>
            <label class="role-option">
                <input type="radio" name="funcion" value="docente" <?= checked($funcion, 'docente') ?> <?= $canEdit ? '' : 'disabled' ?>>
                <span>
                    <strong>Docente</strong>
                    <span>No hay datos extra de docente. Abre la ficha para cargar tomas más adelante.</span>
                </span>
            </label>
        </div>
    </section>

    <section class="panel" id="alumno-fields" <?= $funcion === 'alumno' ? '' : 'hidden' ?>>
        <h2>Datos de alumno</h2>
        <p class="text-secondary">Opcionales. Se pueden completar después en la ficha del alumno.</p>
        <div class="form-grid">
            <label class="form-label">
                Plan
                <select class="form-select" name="plan" <?= $canEdit ? '' : 'disabled' ?>>
                    <option value="">Seleccione...</option>
                    <?php foreach ($planes as $plan) : ?>
                        <?php $label = trim(($plan['orientacion'] ?? '') . ' - ' . ($plan['resolucion'] ?? ''), ' -'); ?>
                        <option value="<?= e($plan['id']) ?>" <?= selected($v($alumno, 'plan'), $plan['id']) ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="form-composite form-ingreso-grid">
                <label class="form-label">
                    Año ing
                    <select class="form-select" name="anio_ingreso" <?= $canEdit ? '' : 'disabled' ?>>
                        <option value="">Seleccione...</option>
                        <option value="1" <?= selected($v($alumno, 'anio_ingreso'), '1') ?>>1</option>
                        <option value="2" <?= selected($v($alumno, 'anio_ingreso'), '2') ?>>2</option>
                        <option value="3" <?= selected($v($alumno, 'anio_ingreso'), '3') ?>>3</option>
                    </select>
                </label>
                <label class="form-label">
                    Sem ing
                    <select class="form-select" name="semestre_ingreso" <?= $canEdit ? '' : 'disabled' ?>>
                        <option value="">Seleccione...</option>
                        <option value="1" <?= selected($v($alumno, 'semestre_ingreso'), '1') ?>>1</option>
                        <option value="2" <?= selected($v($alumno, 'semestre_ingreso'), '2') ?>>2</option>
                    </select>
                </label>
            </div>
            <label class="form-label">Fecha titulacion <input class="form-control" type="date" name="fecha_titulacion" value="<?= e($v($alumno, 'fecha_titulacion')) ?>" <?= $canEdit ? '' : 'disabled' ?>></label>
            <label class="form-check align-self-end">
                <input class="form-check-input" type="checkbox" name="confirmado_direccion" value="1" <?= checked($alumno['confirmado_direccion'] ?? 0) ?> <?= $canEdit ? '' : 'disabled' ?>>
                <span class="form-check-label">Confirmado direccion</span>
            </label>
            <label class="form-label form-wide">Observaciones <textarea class="form-control" name="observaciones" rows="3" <?= $canEdit ? '' : 'disabled' ?>><?= e($v($alumno, 'observaciones')) ?></textarea></label>
        </div>
    </section>

    <?php if ($canEdit) : ?>
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-primary" type="submit">Crear persona</button>
            <a class="btn btn-outline-secondary" href="<?= e(url('/personas')) ?>">Cancelar</a>
        </div>
    <?php else : ?>
        <div class="mb-4">
            <a class="btn btn-outline-secondary" href="<?= e(url('/personas')) ?>">Volver</a>
        </div>
    <?php endif; ?>
</form>

<script>
(() => {
    const fields = document.getElementById('alumno-fields');
    if (!fields) {
        return;
    }
    const sync = () => {
        const selected = document.querySelector('#form-nueva-persona input[name="funcion"]:checked');
        fields.hidden = !selected || selected.value !== 'alumno';
    };
    document.querySelectorAll('#form-nueva-persona input[name="funcion"]').forEach((input) => {
        input.addEventListener('change', sync);
    });
    sync();
})();
</script>
