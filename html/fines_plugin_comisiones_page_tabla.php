<h2>Comisiones Consultadas</h2>

<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Domicilio</th>
            <th>PFID</th>
            <th>Tramo</th>
            <th>Orientación</th>
            <th>Resolución</th>
            <th>Autorizada</th>
            <th>Apertura</th>
            <th>Lista de Alumnos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comisiones as $comision): ?>
            <?php $lista_alumnos_url = admin_url('admin.php?page=fines-plugin-lista-alumnos&comision_id=' . $comision->comision_id); ?>
            <tr>
                <td><?= esc_html($comision->nombre); ?></td>
                <td><?= esc_html($comision->domicilio); ?></td>
                <td><?= esc_html($comision->pfid); ?></td>
                <td><?= esc_html($comision->tramo); ?></td>
                <td><?= esc_html(getAcronym($comision->orientacion)); ?></td>
                <td><?= esc_html($comision->resolucion); ?></td>
                <td><?= esc_html(boolToSiNo($comision->autorizada)); ?></td>
                <td><?= esc_html(boolToSiNo($comision->apertura)); ?></td>
                <td><?= $comision->referentes ?: 'Sin referentes' ?></td>
                <td><a href="<?= esc_url($lista_alumnos_url); ?>" class="button">Alumnos</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>