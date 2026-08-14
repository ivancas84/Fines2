<?php
$acronym = static function (string $text): string {
    $parts = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $out = '';
    foreach ($parts as $part) {
        $out .= mb_substr($part, 0, 1);
    }

    return mb_strtoupper($out);
};

$tomaUrl = static function (array $curso) use ($formBaseUrl): string {
    $comisionParam = trim(
        (string) ($curso['pfid'] ?? '') . ' ' . (string) ($curso['asignatura_codigo'] ?? ''),
    );
    $sep = str_contains($formBaseUrl, '?') ? '&' : '?';

    return $formBaseUrl . $sep . 'comision=' . rawurlencode($comisionParam);
};

// Agrupar por comisión (PFID / id de comisión).
$grupos = [];
foreach ($cursos as $curso) {
    $comisionId = trim((string) ($curso['comision_id'] ?? ''));
    $pfid = trim((string) ($curso['pfid'] ?? ''));
    $groupKey = $comisionId !== '' ? $comisionId : ('pfid:' . ($pfid !== '' ? $pfid : uniqid('c', true)));

    if (!isset($grupos[$groupKey])) {
        $sedeNombre = trim((string) ($curso['sede_nombre'] ?? ''));
        $grupos[$groupKey] = [
            'pfid' => $pfid,
            'sede' => $sedeNombre,
            'domicilio' => trim((string) ($curso['domicilio_label'] ?? '')),
            'cursos' => [],
            'tags' => [
                'tramo' => [],
                'resolucion' => [],
                'orientacion' => [],
            ],
        ];
    }
    if ($grupos[$groupKey]['domicilio'] === '' && trim((string) ($curso['domicilio_label'] ?? '')) !== '') {
        $grupos[$groupKey]['domicilio'] = trim((string) $curso['domicilio_label']);
    }
    if ($grupos[$groupKey]['sede'] === '' && trim((string) ($curso['sede_nombre'] ?? '')) !== '') {
        $grupos[$groupKey]['sede'] = trim((string) $curso['sede_nombre']);
    }
    if ($grupos[$groupKey]['pfid'] === '' && $pfid !== '') {
        $grupos[$groupKey]['pfid'] = $pfid;
    }

    $tramo = trim(($curso['planificacion_anio'] ?? '') . '° / ' . ($curso['planificacion_semestre'] ?? '') . 'C');
    if ($tramo === '° / C') {
        $tramo = '';
    }
    $resolucion = trim((string) ($curso['plan_resolucion'] ?? ''));
    $orientacion = trim((string) ($curso['plan_orientacion'] ?? ''));
    $orientacionShort = $orientacion !== '' ? $acronym($orientacion) : '';

    if ($tramo !== '') {
        $grupos[$groupKey]['tags']['tramo'][$tramo] = $tramo;
    }
    if ($resolucion !== '') {
        $grupos[$groupKey]['tags']['resolucion'][$resolucion] = $resolucion;
    }
    if ($orientacionShort !== '') {
        $grupos[$groupKey]['tags']['orientacion'][$orientacionShort] = $orientacion;
    }

    $grupos[$groupKey]['cursos'][] = $curso;
}
?>
<section class="tp-hero">
    <div class="tp-hero-text">
        <h1>Toma de posesión</h1>
        <p class="tp-lead">
            Busca el curso correspondiente y hace clic en
            <strong>Tomar Posesión</strong> para completar el formulario.
        </p>
    </div>
</section>

<?php if ($calendario === null) : ?>
    <div class="tp-empty">
        <strong>Sin calendarios</strong>
        <span>No hay calendarios cargados en el sistema.</span>
    </div>
<?php elseif ($cursos === []) : ?>
    <div class="tp-empty">
        <strong>Sin cursos</strong>
        <span>No se encontraron comisiones autorizadas y publicadas para tomar posesión.</span>
    </div>
<?php else : ?>
    <div class="tp-toolbar">
        <label class="visually-hidden" for="tp-filter">Buscar curso</label>
        <input class="form-control tp-filter"
               type="search"
               id="tp-filter"
               placeholder="Buscar por PFID, sede, materia u horario…"
               autocomplete="off">
        <p class="tp-toolbar-hint text-secondary small mb-0" id="tp-filter-hint" hidden></p>
    </div>

    <div class="tp-list" id="tp-list">
        <?php foreach ($grupos as $grupo) : ?>
            <?php
            $tituloComision = $grupo['pfid'] !== '' ? $grupo['pfid'] : 'Sin PFID';
            $groupSearch = mb_strtolower(implode(' ', array_filter([
                $grupo['pfid'],
                $grupo['sede'],
                $grupo['domicilio'],
                implode(' ', $grupo['tags']['tramo']),
                implode(' ', $grupo['tags']['resolucion']),
                implode(' ', array_keys($grupo['tags']['orientacion'])),
            ])));
            ?>
            <section class="tp-comision" data-group-search="<?= e($groupSearch) ?>">
                <header class="tp-comision-header">
                    <div class="tp-comision-header-main">
                        <h2 class="tp-comision-title"><?= e($tituloComision) ?></h2>
                        <?php if ($grupo['sede'] !== '') : ?>
                            <p class="tp-comision-sede mb-0"><?= e($grupo['sede']) ?></p>
                        <?php endif; ?>
                        <?php if ($grupo['domicilio'] !== '') : ?>
                            <p class="tp-comision-domicilio mb-0"><?= e($grupo['domicilio']) ?></p>
                        <?php endif; ?>
                        <?php if ($grupo['tags']['tramo'] !== [] || $grupo['tags']['resolucion'] !== [] || $grupo['tags']['orientacion'] !== []) : ?>
                            <div class="tp-comision-tags">
                                <?php foreach ($grupo['tags']['tramo'] as $tramo) : ?>
                                    <span class="tp-tag"><span class="tp-tag-label">Tramo</span> <?= e($tramo) ?></span>
                                <?php endforeach; ?>
                                <?php foreach ($grupo['tags']['resolucion'] as $resolucion) : ?>
                                    <span class="tp-tag"><span class="tp-tag-label">Res.</span> <?= e($resolucion) ?></span>
                                <?php endforeach; ?>
                                <?php foreach ($grupo['tags']['orientacion'] as $short => $full) : ?>
                                    <span class="tp-tag" title="<?= e((string) $full) ?>">
                                        <span class="tp-tag-label">Orient.</span> <?= e((string) $short) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="tp-comision-count">
                        <?= e((string) count($grupo['cursos'])) ?>
                        curso<?= count($grupo['cursos']) === 1 ? '' : 's' ?>
                    </span>
                </header>

                <div class="tp-cards">
                    <?php foreach ($grupo['cursos'] as $curso) : ?>
                        <?php
                        $asignatura = trim((string) ($curso['asignatura_nombre'] ?? ''));
                        $codigo = trim((string) ($curso['asignatura_codigo'] ?? ''));
                        $pfid = trim((string) ($curso['pfid'] ?? ''));
                        $horario = trim((string) ($curso['descripcion_horario'] ?? ''));
                        $tramo = trim(($curso['planificacion_anio'] ?? '') . '° / ' . ($curso['planificacion_semestre'] ?? '') . 'C');
                        if ($tramo === '° / C') {
                            $tramo = '';
                        }
                        $horas = $curso['disposicion_horas_catedra'] ?? null;
                        $horasLabel = ($horas !== null && $horas !== '') ? ((string) $horas . ' hs') : '';
                        $resolucion = trim((string) ($curso['plan_resolucion'] ?? ''));
                        $orientacion = trim((string) ($curso['plan_orientacion'] ?? ''));
                        $cardSearch = mb_strtolower(implode(' ', array_filter([
                            $grupo['pfid'],
                            $grupo['sede'],
                            $grupo['domicilio'],
                            $asignatura,
                            $codigo,
                            $pfid,
                            $horario,
                            $tramo,
                            $resolucion,
                            $orientacion,
                            $horasLabel,
                        ])));
                        ?>
                        <article class="tp-card" data-search="<?= e($cardSearch) ?>">
                            <div class="tp-card-main">
                                <div class="tp-card-title-row">
                                    <h3 class="tp-card-title">
                                        <?= e($asignatura !== '' ? $asignatura : 'Asignatura sin nombre') ?>
                                    </h3>
                                    <?php if ($codigo !== '' || $horasLabel !== '') : ?>
                                        <div class="tp-card-code-row">
                                            <?php if ($codigo !== '') : ?>
                                                <span class="tp-pill tp-pill-code"><?= e($codigo) ?></span>
                                            <?php endif; ?>
                                            <?php if ($horasLabel !== '') : ?>
                                                <span class="tp-pill tp-pill-hours"><?= e($horasLabel) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="tp-card-horario">
                                    <span class="tp-horario-icon" aria-hidden="true">⏱</span>
                                    <div>
                                        <span class="tp-tag-label d-block">Horario</span>
                                        <span><?= e($horario !== '' ? $horario : 'Sin horario cargado') ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="tp-card-action">
                                <a class="btn btn-primary tp-btn-tomar"
                                   href="<?= e($tomaUrl($curso)) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Tomar posesión
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <div class="tp-empty tp-empty-filter" id="tp-no-results" hidden>
        <strong>Sin resultados</strong>
        <span>No hay cursos que coincidan con la búsqueda.</span>
    </div>

    <script>
    (function () {
        var input = document.getElementById('tp-filter');
        var list = document.getElementById('tp-list');
        var noResults = document.getElementById('tp-no-results');
        var hint = document.getElementById('tp-filter-hint');
        if (!input || !list) return;

        function normalize(value) {
            return (value || '').toString().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        function applyFilter() {
            var q = normalize(input.value.trim());
            var cards = list.querySelectorAll('.tp-card');
            var groups = list.querySelectorAll('.tp-comision');
            var visibleCards = 0;

            cards.forEach(function (card) {
                var hay = normalize(card.getAttribute('data-search') || '');
                var show = q === '' || hay.indexOf(q) !== -1;
                card.hidden = !show;
                if (show) visibleCards++;
            });

            groups.forEach(function (group) {
                var any = false;
                group.querySelectorAll('.tp-card').forEach(function (card) {
                    if (!card.hidden) any = true;
                });
                group.hidden = !any;
            });

            if (noResults) noResults.hidden = visibleCards > 0;
            if (hint) {
                if (q === '') {
                    hint.hidden = true;
                    hint.textContent = '';
                } else {
                    hint.hidden = false;
                    hint.textContent = visibleCards === 1
                        ? '1 curso encontrado'
                        : visibleCards + ' cursos encontrados';
                }
            }
        }

        input.addEventListener('input', applyFilter);
    })();
    </script>
<?php endif; ?>
