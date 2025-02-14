<h2>Calificaciones</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Asignatura</th>
            <th>Año</th>
            <th>Semestre</th>
            <th>Nota Final</th>
            <th>CREC</th>
            <th>Año</th>
            <th>Semestre</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($calificaciones as $cal): ?>
            <tr>
                <td><?= $cal->nombre ?></td>
                <td><?= $cal->anio ?></td>
                <td><?= $cal->semestre ?></td>
                <td><?= $cal->nota_final ?></td>
                <td><?= $cal->crec ?></td>
                <td><?= $cal->anio ?></td>
                <td><?= $cal->semestre ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
