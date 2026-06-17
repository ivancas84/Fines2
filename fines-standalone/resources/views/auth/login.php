<section class="login-panel">
    <h1>Fines</h1>
    <p class="text-secondary">Ingresar al administrador</p>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($googleEnabled)) : ?>
        <a class="btn btn-outline-primary w-100 mb-3" href="<?= e(url('/login/google')) ?>">Ingresar con Google</a>
    <?php else : ?>
        <div class="alert alert-warning">Google todavia no esta configurado.</div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/login')) ?>">
        <?= $csrf->field() ?>
        <label class="form-label w-100">
            Email
            <input class="form-control" type="email" name="email" autocomplete="username" required autofocus>
        </label>
        <label class="form-label w-100 mt-3">
            Contrasena
            <input class="form-control" type="password" name="password" autocomplete="current-password" required>
        </label>
        <button class="btn btn-primary w-100 mt-4" type="submit">Ingresar</button>
    </form>
</section>
