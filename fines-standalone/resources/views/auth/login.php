<section class="login-panel">
    <h1>Fines</h1>
    <p class="text-secondary">Ingresar al administrador</p>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($googleEnabled)) : ?>
        <a class="btn btn-primary w-100" href="<?= e(url('/login/google')) ?>">Ingresar con Google</a>
    <?php else : ?>
        <div class="alert alert-warning">Google todavia no esta configurado.</div>
    <?php endif; ?>
</section>
