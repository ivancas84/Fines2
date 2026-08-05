<?php
$user = $auth->user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = rtrim((string) ($_ENV['APP_BASE_PATH'] ?? ''), '/');
if ($basePath !== '' && str_starts_with($currentPath, $basePath)) {
    $currentPath = substr($currentPath, strlen($basePath)) ?: '/';
}
$currentPath = '/' . trim($currentPath, '/');
$navItems = [
    '/personas' => 'Personas',
    '/comisiones' => 'Comisiones',
    '/sedes' => 'Sedes',
    '/calendarios' => 'Calendarios',
    '/cursos' => 'Cursos',
    '/informes' => 'Informes',
    '/programafines' => 'ProgramaFines',
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($title ?? 'Fines') . ' - Fines') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(url('/assets/app.css')) ?>" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@1.9.12" defer></script>
</head>
<body data-base-url="<?= e(url('/')) ?>">
<?php if ($auth->check()) : ?>
    <div class="app-shell">
        <aside class="app-sidebar">
            <a class="app-brand" href="<?= e(url('/')) ?>">Fines</a>
            <nav class="nav flex-column gap-1">
                <?php foreach ($navItems as $path => $label) : ?>
                    <?php $isActive = $currentPath === $path || str_starts_with($currentPath, $path . '/'); ?>
                    <a class="nav-link<?= $isActive ? ' active' : '' ?>" href="<?= e(url($path)) ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
                        <?= e($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <form class="mt-auto" method="post" action="<?= e(url('/logout')) ?>">
                <?= $csrf->field() ?>
                <div class="small text-secondary mb-2"><?= e($user['nombre'] ?? '') ?></div>
                <button class="btn btn-outline-secondary w-100" type="submit">Salir</button>
            </form>
        </aside>
        <main class="app-main">
            <?= $content ?>
        </main>
    </div>
<?php else : ?>
    <main class="auth-main">
        <?= $content ?>
    </main>
<?php endif; ?>
<script src="<?= e(url('/assets/app.js')) ?>" defer></script>
</body>
</html>
