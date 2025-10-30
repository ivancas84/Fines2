<?php

use SqlOrganize\Utils\ValueTypesUtils;
?>
<div class="wrap">
    <h1>Constancia General</h1>
    <form method="POST" action="https://planfines2.com.ar/scripts/constancia_general.php">
        <table class="form-table">
            <tr>
                <th><label>Título:</label></th>
                <td><input type="text" name="titulo" value="CONSTANCIA DE VACANTE" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Contenido:</label></th>
                <td><input type="text" name="titulo" value="TIENE UNA VACANTE EN ESTE ESTABLECIMIENTO" class="regular-text" required></td>
            </tr>
		    <tr>
                <th><label>Nombres:</label></th>
                <td><input type="text" name="nombres" value="<?= $nombres ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Apellidos:</label></th>
                <td><input type="text" name="apellidos" value="<?= $apellidos ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Número de Documento:</label></th>
                <td><input type="text" name="numero_documento" value="<?= $numero_documento ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Fecha:</label></th>
                <td><input type="text" name="fecha" value="<?= esc_attr($fecha) ?>" class="regular-text" readonly></td>
            </tr>
            <tr>
                <th><label>Presentado a:</label></th>
                <td><input type="text" name="presentado" value="Quien Corresponda" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Observaciones:</label></th>
                <td><textarea name="observaciones" rows="4" class="large-text"></textarea></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>