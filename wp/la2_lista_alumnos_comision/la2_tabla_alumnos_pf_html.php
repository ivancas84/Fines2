<?php
/** @var array<int, array<string,mixed>> $alumnos_pf */ 
?>

<h2>Cantidad de Alumnos en Programa Fines <?=$comision->getLabel() ?>: <?=count($alumnos_pf)?></h2>
<table class="wp-list-table widefat striped">   
    <thead>
        <tr>            
            <th>Nombre</th>
            <th>DNI</th>
            <th>Fecha Nacimiento</th>
            <th>Email</th>

        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnos_pf as $apf): ?>
            <tr>
                <td><?= esc_html($apf["nombre"] ?? "?"); ?></td>
                <td><?= esc_html($apf["numero_documento"] ?? "?"); ?></td>
                <td><?= esc_html($apf["fecha_nacimiento"] ?? "?"); ?></td>
                <td><?= esc_html($apf["email"] ?? "?"); ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>