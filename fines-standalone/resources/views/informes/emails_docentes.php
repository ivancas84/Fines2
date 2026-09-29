<?php
$calendarioLabel = static function (array $calendario): string {
    $label = trim((string) ($calendario['label'] ?? ''));
    if ($label !== '') {
        return $label;
    }

    return trim(implode(' ', array_filter([
        trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '')),
        (string) ($calendario['descripcion'] ?? ''),
    ]))) ?: (string) ($calendario['id'] ?? '');
};
$emailCount = count($emails);
?>
<div class="page-header">
    <div>
        <h1>Emails de docentes</h1>
        <p class="text-secondary mb-0">Docentes con toma aprobada en el calendario elegido.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= e(url('/informes')) ?>">Volver</a>
</div>

<section class="panel">
    <h2>Calendario</h2>
    <p class="text-secondary mb-3">
        Se toman las tomas con estado «Aprobada» de cursos cuya comisión pertenece al calendario.
        De cada docente se incluyen <strong>email</strong> y <strong>email ABC</strong>, sin vacíos ni repetidos.
    </p>
    <?php if ($calendarios === []) : ?>
        <div class="empty-state">No hay calendarios cargados.</div>
    <?php else : ?>
        <form method="get" action="<?= e(url('/informes/emails-docentes')) ?>">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label" for="calendario">Calendario</label>
                    <select class="form-select" name="calendario" id="calendario">
                        <?php foreach ($calendarios as $calendario) : ?>
                            <option value="<?= e((string) $calendario['id']) ?>" <?= selected($selectedCalendario, $calendario['id']) ?>>
                                <?= e($calendarioLabel($calendario)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-primary" type="submit">Consultar</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php if ($selectedCalendario !== '') : ?>
    <section class="panel">
        <h2>Listado</h2>
        <p class="text-secondary mb-2">
            <?= e((string) $docentes) ?> docentes con toma aprobada.
            <?= e((string) $emailCount) ?> emails distintos.
            <?php if ($sinEmail > 0) : ?>
                <?= e((string) $sinEmail) ?> sin email ni email ABC.
            <?php endif; ?>
        </p>
        <?php if ($emails === []) : ?>
            <div class="empty-state">No hay emails para este calendario.</div>
        <?php else : ?>
            <p class="text-secondary">Separados por punto y coma para pegar en Para o CCO.</p>
            <textarea class="form-control font-monospace"
                      id="emails-docentes"
                      rows="<?= e((string) min(14, max(4, (int) ceil($emailCount / 2)))) ?>"
                      spellcheck="false"><?= e($emailsTexto) ?></textarea>
            <button class="btn btn-outline-dark mt-3"
                    type="button"
                    data-copy-target="#emails-docentes">Copiar</button>
        <?php endif; ?>
    </section>
<?php endif; ?>
