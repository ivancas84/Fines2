<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($title ?? 'Fines') . ' - Fines') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(url('/assets/app.css')) ?>" rel="stylesheet">
</head>
<body class="public-body" data-base-url="<?= e(url('/')) ?>">
<main class="public-main">
    <div class="public-shell">
        <header class="public-header">
            <a class="public-brand" href="<?= e(url('/toma-posesion')) ?>">
                <span class="public-brand-mark">F</span>
                <span class="public-brand-text">Programa Fines</span>
            </a>
            <span class="public-header-label">CENS 462</span>
        </header>
        <?= $content ?>
        <footer class="public-footer">
            <span>Comisiones autorizadas y publicadas · Programa Fines</span>
        </footer>
    </div>
</main>
</body>
</html>
