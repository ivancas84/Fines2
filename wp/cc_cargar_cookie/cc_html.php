<?php
/** @var string $phpsess Valor de la cookie */
?>
<div class="wrap">
    <h1>Cargar Cookie ProgramaFines</h1>
    <p>Descargar extensión para visualizar cookie, ejemplo EditThisCookie (V3).<p>
    <p>Ejecutar EditThisCookie desde una sesión abierta de programafines para ver el valor de PHPSESS.<p>
    <p>Cargar el valor de PHPSESS aquí</p>
    <p><strong> Valor actual de PHPSESS <?= $phpsess ?? "No se encuentra cargado" ?></strong></p>
    <form method="POST">
        <table>
            <tr>
                <th><label>Nuevo valor de PHPSESS:</label></th>
                <td><input type="text" name="phpsess" value="" class="regular-text"></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>