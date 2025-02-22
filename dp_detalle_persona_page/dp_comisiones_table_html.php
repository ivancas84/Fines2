<h2>Comisiones</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Sede</th>
            <th>Código Interno</th>
            <th>Pfid</th>
            <th>Periodo</th>
            <th>Tramo</th>
            <th>Plan</th>
            <th>Modalidad</th>
            <th>Estado</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($comisiones as $cal): ?>
            <tr>
                <td><?= $cal->detalle_sede ?></td>
                <td><?= $cal->codigo_interno ?></td>
                <td><?= $cal->pfid ?></td>
                <td><?= $cal->periodo ?></td>
                <td><?= $cal->tramo ?></td>
                <td><?= $cal->detalle_plan ?></td>
                <td><?= $cal->detalle_modalidad ?></td>
                <td><?= $cal->estado ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
