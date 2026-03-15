<h2>Lista de Comisiones Autorizadas para tomar posesión</h2>

<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Domicilio</th>
            <th>PFID</th>
            <th>Asignatura</th>
            <th>Horario</th>
            <th>Toma Posesión</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($cursos as $curso): ?>
            <?  $formulario_toma_url = esc_url('https://planfines2.com.ar/wp/toma-de-posesion/?comision=' . rawurlencode($curso->comision_?->pfid . ' ' . $curso->disposicion_?->asignatura_?->codigo));  ?>

            <tr>
                <td><?= esc_html($curso->comision_?->sede_?->nombre); ?></td>
                <td><?= esc_html($curso->comision_?->sede_?->domicilio_?->getLabel()); ?></td>
                <td><?= esc_html($curso->comision_?->pfid); ?></td>
                <td><?= esc_html($curso->disposicion_?->getLabel()); ?></td>
                <td><?= esc_html($curso->descripcion_horario); ?></td>
                <td>
                    <a href="<?= $formulario_toma_url ?>" class="button">Tomar</a> 
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>