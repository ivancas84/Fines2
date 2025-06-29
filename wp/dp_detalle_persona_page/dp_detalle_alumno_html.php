<h2>Detalle Alumno</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>  
            <th>Debe empezar en</th>
            <th>Plan en curso</th>
            <th>DNI</th>
            <th>Constancia</th>
            <th>Certificado</th>
            <th>Partida</th>
            <th>Previas Completas</th>
            <th>Confirmado Dirección</th>
        </tr>
    </thead>
    <tbody>
            <tr>
                <td><?= $alumno->anio_ingreso ?></td>
                <td><?= $alumno->detalle_plan ?></td>
                <td><?= $alumno->tiene_dni ? 'SI' : '' ?></td>
                <td><?= $alumno->tiene_constancia ? 'SI' : '' ?></td>
                <td><?= $alumno->tiene_certificado ? 'SI' : '' ?></td>
                <td><?= $alumno->tiene_partida ? 'SI' : '' ?></td>
                <td><?= $alumno->previas_completas ? 'SI' : '' ?></td>
                <td><?= $alumno->confirmado_direccion ? 'SI' : '' ?></td>
            </tr>
    </tbody>
</table>
