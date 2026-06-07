<?php $user = $auth->user(); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($title ?? 'Constancias') . ' - Constancias') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(url('/assets/app.css?v=' . (string) filemtime($config->rootPath() . '/public/assets/app.css'))) ?>" rel="stylesheet">
</head>
<body>
<?php if ($auth->check()) : ?>
    <div class="app-shell">
        <aside class="app-sidebar">
            <a class="app-brand" href="<?= e(url('/constancias')) ?>">
                <img src="<?= e(url('/assets/abc-constancias-logo.png')) ?>" alt="ABC Constancias">
            </a>
            <nav class="nav flex-column gap-1">
                <a class="nav-link" href="<?= e(url('/constancias')) ?>">Constancias</a>
                <a class="nav-link" href="<?= e(url('/')) ?>">Emitidas</a>
                <a class="nav-link" href="<?= e(url('/establecimiento')) ?>">Establecimiento</a>
            </nav>
            <form class="mt-auto" method="post" action="<?= e(url('/logout')) ?>">
                <?= $csrf->field() ?>
                <div class="small text-secondary mb-2"><?= e($user['nombre'] ?? '') ?></div>
                <button class="btn btn-outline-secondary w-100" type="submit">Salir</button>
            </form>
        </aside>
        <main class="app-main"><?= $content ?></main>
    </div>
<?php else : ?>
    <main class="auth-main"><?= $content ?></main>
<?php endif; ?>
<script src="<?= e(url('/assets/app.js?v=' . (string) filemtime($config->rootPath() . '/public/assets/app.js'))) ?>" defer></script>
</body>
</html>
