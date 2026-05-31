<?php if (!$submitted) : ?>
    <div class="empty-state">Ingresa un texto para buscar personas.</div>
<?php elseif ($personas === []) : ?>
    <div class="empty-state">No se encontraron personas.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle app-table">
            <thead>
            <tr>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>DNI</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Email ABC</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($personas as $persona) : ?>
                <tr>
                    <td><?= e($persona['apellidos'] ?? '') ?></td>
                    <td><?= e($persona['nombres'] ?? '') ?></td>
                    <td><?= e($persona['numero_documento'] ?? '') ?></td>
                    <td><?= e($persona['telefono'] ?? '') ?></td>
                    <td><?= e($persona['email'] ?? '') ?></td>
                    <td><?= e($persona['email_abc'] ?? '') ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/personas/' . $persona['id'] . '/alumno')) ?>">Alumno</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
