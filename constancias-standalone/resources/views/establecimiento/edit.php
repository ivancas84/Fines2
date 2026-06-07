<?php
$v = static fn (?array $row, string $key): string => $row === null ? '' : (string) ($row[$key] ?? '');
$establecimientoForm = form_values($establecimiento ?? [], [
    'nombre',
    'localidad',
    'modalidad_principal',
    'orientacion_principal',
    'resolucion_principal',
]);
$localidad = (string) ($establecimientoForm['localidad'] ?? '') ?: 'La Plata';
$modalidad = (string) ($establecimientoForm['modalidad_principal'] ?? '') ?: 'Programa Fines 2 Trayecto Secundario';
$orientacion = (string) ($establecimientoForm['orientacion_principal'] ?? '') ?: 'Ciencias Sociales';
$resolucion = (string) ($establecimientoForm['resolucion_principal'] ?? '') ?: '2993/22';
$imageCell = static function (?array $row, string $key, string $tipo) use ($v): string {
    $path = $v($row, $key);
    if ($path === '') {
        return 'Sin imagen';
    }

    return e($path) . ' <a class="btn btn-sm btn-outline-primary ms-2" href="' . e(url('/establecimiento/imagen/' . $tipo)) . '" target="_blank" rel="noopener">Ver</a>';
};
?>
<div class="page-header">
    <div>
        <h1>Establecimiento</h1>
        <p class="text-secondary mb-0">Datos usados al emitir constancias.</p>
    </div>
</div>

<?php if (!empty($notice)) : ?>
    <div class="alert alert-success"><?= e($notice) ?></div>
<?php endif; ?>
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel">
    <form class="js-prevent-double-submit" method="post" action="<?= e(url('/establecimiento')) ?>" enctype="multipart/form-data">
        <?= $csrf->field() ?>
        <div class="form-grid">
            <label class="form-label form-span-2">Nombre <input class="form-control" name="nombre" value="<?= e($establecimientoForm['nombre'] ?? '') ?>" readonly required></label>
            <label class="form-label form-span-2">Localidad <input class="form-control" name="localidad" value="<?= e($localidad) ?>" readonly></label>
            <label class="form-label form-span-2">Modalidad principal <input class="form-control" name="modalidad_principal" value="<?= e($modalidad) ?>" required></label>
            <label class="form-label">Orientación principal <input class="form-control" name="orientacion_principal" value="<?= e($orientacion) ?>" required></label>
            <label class="form-label">Resolución principal <input class="form-control" name="resolucion_principal" value="<?= e($resolucion) ?>" required></label>
            <label class="form-label form-span-2">Logo <input class="form-control" type="file" name="logo" accept="image/png,image/jpeg,image/webp"></label>
            <label class="form-label form-span-2">Firma del director <input class="form-control" type="file" name="firma_director" accept="image/png,image/jpeg,image/webp"></label>
            <label class="form-label form-span-2">Sello oval <input class="form-control" type="file" name="sello_oval" accept="image/png,image/jpeg,image/webp"></label>
        </div>
        <div class="table-responsive mt-3">
            <table class="table app-table">
                <tbody>
                <tr><th>Logo actual</th><td><?= $imageCell($establecimiento, 'logo_path', 'logo') ?></td></tr>
                <tr><th>Firma actual</th><td><?= $imageCell($establecimiento, 'firma_director_path', 'firma-director') ?></td></tr>
                <tr><th>Sello actual</th><td><?= $imageCell($establecimiento, 'sello_oval_path', 'sello-oval') ?></td></tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-primary" type="submit" data-submitting-text="Guardando...">Guardar establecimiento</button>
    </form>
</section>
