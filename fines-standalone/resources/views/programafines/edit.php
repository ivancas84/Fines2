<div class="page-header">
    <div>
        <h1>ProgramaFines</h1>
        <p class="text-secondary mb-0">Conexión temporal con programafines.ar para consultar y sincronizar alumnos.</p>
    </div>
    <?php if ($connected) : ?>
        <span class="badge text-bg-success fs-6">Conectado</span>
    <?php else : ?>
        <span class="badge text-bg-secondary fs-6">Desconectado</span>
    <?php endif; ?>
</div>

<?php if (!empty($notice)) : ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
<?php if (!empty($error)) : ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<section class="panel">
    <h2>Sesión de acceso</h2>
    <p>
        Iniciá sesión en <a href="https://www.programafines.ar/" target="_blank" rel="noopener">programafines.ar</a>,
        abrí las herramientas del navegador y copiá el valor de la cookie <code>PHPSESS</code>.
        Por seguridad, el navegador no permite que este sistema lea automáticamente una cookie de otro dominio.
    </p>
    <p class="small text-secondary">
        La cookie se conserva solamente en la sesión autenticada de este navegador, no se guarda en la base de datos
        y se elimina al desconectar o cerrar sesión en Fines.
    </p>

    <?php if ($connected) : ?>
        <div class="alert alert-success">
            Sesión actual: <code><?= e($maskedSession) ?></code><br>
            Período remoto configurado: <strong><?= e((string) $periodo) ?></strong>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <form method="post" action="<?= e(url('/programafines')) ?>">
                <?= $csrf->field() ?>
                <label class="form-label">
                    Reemplazar PHPSESS
                    <input class="form-control" type="password" name="session_id" autocomplete="off" required>
                </label>
                <button class="btn btn-primary" type="submit">Verificar y reemplazar</button>
            </form>
            <form class="align-self-end" method="post" action="<?= e(url('/programafines/desconectar')) ?>">
                <?= $csrf->field() ?>
                <button class="btn btn-outline-danger" type="submit">Desconectar</button>
            </form>
        </div>
    <?php else : ?>
        <form method="post" action="<?= e(url('/programafines')) ?>">
            <?= $csrf->field() ?>
            <label class="form-label">
                Valor de PHPSESS
                <input class="form-control" type="password" name="session_id" autocomplete="off" required>
            </label>
            <button class="btn btn-primary" type="submit">Verificar y conectar</button>
        </form>
    <?php endif; ?>
</section>
