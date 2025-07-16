<h2>Comisiones Consultadas <?=count($alumnosComision)?></h2>
<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>DNI</th>
            <th>Estado</th>
            <th>Activo</th>
            <th>Nota</th>

        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnosComision as $ac): ?>
            <? $url = admin_url('admin.php?page=fines-plugin-administrar-toma-comision&comision_id=' . $ac->id); ?>
            <tr>
                <td><?= esc_html($ac->alumno_?->persona_->nombres ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_?->persona_->apellidos ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_?->persona_->numero_documento ?? "?"); ?></td>
                <td><?= esc_html($ac->estado ?? "?"); ?></td>
                <td><?= esc_html(boolToSiNo($ac->activo)); ?></td>
                <td><?= esc_html($ac->_label ?? "?"); ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>