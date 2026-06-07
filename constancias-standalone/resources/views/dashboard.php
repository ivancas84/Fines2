<div class="page-header">
    <div>
        <h1>Constancias emitidas</h1>
        <p class="text-secondary mb-0"><?= e($establecimiento['nombre'] ?? 'Sin establecimiento configurado') ?></p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('/constancias')) ?>">Generar constancia</a>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="filters-bar" method="get" action="<?= e(url('/')) ?>">
        <input class="form-control" name="q" value="<?= e(form_value('q', $search ?? '')) ?>" placeholder="Buscar por nombre, apellido o DNI" autocomplete="off">
        <button class="btn btn-primary" type="submit">Buscar</button>
        <?php if (($search ?? '') !== '') : ?>
            <a class="btn btn-outline-secondary" href="<?= e(url('/')) ?>">Limpiar</a>
        <?php endif; ?>
    </form>

    <?php if (($constancias ?? []) === []) : ?>
        <div class="empty-state"><?= ($search ?? '') === '' ? 'No hay constancias emitidas.' : 'No hay resultados para la busqueda.' ?></div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover app-table">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Titular</th>
                    <th>DNI</th>
                    <th>Titulo</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($constancias as $constancia) : ?>
                    <tr>
                        <td><?= e($constancia['creado_en'] ?? '') ?></td>
                        <td><?= e(trim(($constancia['apellidos'] ?? '') . ', ' . ($constancia['nombres'] ?? ''), ', ')) ?></td>
                        <td><?= e($constancia['numero_documento'] ?? '') ?></td>
                        <td><?= e($constancia['titulo'] ?? '') ?></td>
                        <td>
                            <?php if (empty($constancia['anulado_en'])) : ?>
                                <a href="<?= e(url('/validar-constancia?clave=' . rawurlencode((string) $constancia['clave']))) ?>" target="_blank" rel="noopener">Validar</a>
                                <span class="text-secondary mx-1">|</span>
                                <a href="<?= e(url('/validar-constancia/descargar?clave=' . rawurlencode((string) $constancia['clave']))) ?>">Descargar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $pagination = $pagination ?? ['page' => 1, 'pages' => 1, 'total' => count($constancias ?? []), 'per_page' => 20];
        $currentPage = (int) ($pagination['page'] ?? 1);
        $pages = (int) ($pagination['pages'] ?? 1);
        $total = (int) ($pagination['total'] ?? 0);
        $pageUrl = static function (int $page) use ($search): string {
            $params = ['page' => $page];
            if (($search ?? '') !== '') {
                $params['q'] = $search;
            }

            return url('/?' . http_build_query($params));
        };
        ?>
        <div class="pagination-bar">
            <div class="text-secondary small"><?= e($total) ?> constancia<?= $total === 1 ? '' : 's' ?></div>
            <?php if ($pages > 1) : ?>
                <nav aria-label="Paginacion de constancias">
                    <ul class="pagination mb-0">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= e($pageUrl(max(1, $currentPage - 1))) ?>">Anterior</a>
                        </li>
                        <?php for ($page = max(1, $currentPage - 2); $page <= min($pages, $currentPage + 2); $page++) : ?>
                            <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= e($pageUrl($page)) ?>"><?= e($page) ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= e($pageUrl(min($pages, $currentPage + 1))) ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
