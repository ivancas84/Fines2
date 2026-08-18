<section class="panel">
    <h1>Pagina no encontrada</h1>
    <p class="text-secondary<?= empty($debug) ? ' mb-0' : '' ?>">La ruta solicitada no existe.</p>
    <?php if (!empty($debug) && is_array($debug)) : ?>
        <pre class="mt-3 mb-0 small bg-light border rounded p-3"><?php
            echo e("method: " . (string) ($debug['method'] ?? '') . "\n");
            echo e("request_uri: " . (string) ($debug['request_uri'] ?? '') . "\n");
            echo e("script_name: " . (string) ($debug['script_name'] ?? '') . "\n");
            echo e("script_filename: " . (string) ($debug['script_filename'] ?? '') . "\n");
            echo e("app_base_path: " . (string) ($debug['app_base_path'] ?? '') . "\n");
            echo e("path que busca el router: " . (string) ($debug['path'] ?? '') . "\n\n");
            echo "Rutas registradas:\n";
            foreach (($debug['routes'] ?? []) as $route) {
                echo e((string) $route) . "\n";
            }
        ?></pre>
    <?php endif; ?>
</section>
