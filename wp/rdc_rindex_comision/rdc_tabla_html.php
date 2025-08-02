<h2>Rindex Comisión <?=$comision->getLabel()?></h2>
<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>DNI</th>
            <? foreach ($informe as $curso): ?>
                <th>
                    <?= esc_html($curso['asignatura'] . $curso['tramo']) ?><br>
                    <?= esc_html($curso['docente']) ?>
                </th>
            <? endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnosComision as $alumnoComision): ?>
            <tr>
                <td><?= esc_html($alumnoComision->alumno_?->persona_?->apellidos); ?></td>
                <td><?= esc_html($alumnoComision->alumno_?->persona_?->nombres); ?></td>
                <td><?= esc_html($alumnoComision->alumno_?->persona_?->numero_documento); ?></td>
                <? for ($i = 0; $i < count($informe); $i++): ?>
                <td><?= esc_html($alumnoComision->notas[$i]) ?></td>
                <? endfor; ?>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>