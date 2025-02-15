<?php
function dp_calificaciones_table_html($calificaciones, $title) {
?>
<h2><?=$title?></h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Asignatura</th>
            <th>Tramo</th>
            <th>Nota Final</th>
            <th>Crec</th>
            <th>Pfid</th>
            <th>Período</th>
            <th>Docente</th>
            <th>Orientacion</th>
            <th>Resolución</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($calificaciones as $cal): ?>
            <tr>
                <td><?= $cal->nombre ?></td>
                <td><?= $cal->tramo ?></td>
                <td><?= $cal->nota_final ?></td>
                <td><?= $cal->crec ?></td>
                <td><?= $cal->pfid ?></td>
                <td><?= $cal->periodo ?></td>
                <td><?= $cal->docente ?></td>
                <td><?= $cal->orientacion ?></td>
                <td><?= $cal->resolucion ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
}
?>