<section class="login-panel">
    <h1>Constancias</h1>
    <p class="text-secondary">Ingresar al sistema</p>
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= e(url('/login')) ?>">
        <?= $csrf->field() ?>
        <label class="form-label w-100">Email <input class="form-control" type="email" name="email" autocomplete="username" required autofocus></label>
        <label class="form-label w-100 mt-3">Contrasena <input class="form-control" type="password" name="password" autocomplete="current-password" required></label>
        <button class="btn btn-primary w-100 mt-4" type="submit">Ingresar</button>
    </form>
</section>
