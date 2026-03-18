<h2>Comisiones Consultadas</h2>

<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Email ABC</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($personas as $persona): ?>
            
            <tr>
                <td><?= esc_html($persona->nombres); ?></td>
                <td><?= esc_html($persona->apellidos); ?></td>
                <td><?= esc_html($persona->numero_documento); ?></td>
                <td><?= esc_html($persona->telefono); ?></td>
                <td><?= esc_html($persona->email); ?></td>
                <td><?= esc_html($persona->email_abc); ?></td>
                <td>
                    <a href="<?= esc_url(admin_url('admin.php?page='.FINES_PLUGIN.'-aa4&persona_id=' . $persona->id)); ?>" class="button">Alumno</a>
                    <a href="<?= esc_url(admin_url('admin.php?page='.FINES_PLUGIN.'-ad3&persona_id=' . $persona->id)); ?>" class="button">Docente</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>