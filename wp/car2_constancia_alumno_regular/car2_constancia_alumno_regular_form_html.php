<?php

use SqlOrganize\Utils\ValueTypesUtils;
?>
<div class="wrap">
    <h1>Formulario de Datos del Alumno</h1>
    <form method="POST" action="https://planfines2.com.ar/constancia_alumno_regular.php">
        <table class="form-table">
		<tr>
                <th><label>Nombres:</label></th>
                <td><input type="text" name="nombres" value="<?= esc_attr( mb_strtoupper($persona->nombres, 'UTF-8')) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Apellidos:</label></th>
                <td><input type="text" name="apellidos" value="<?= esc_attr( mb_strtoupper($persona->apellidos, 'UTF-8')) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Número de Documento:</label></th>
                <td><input type="text" name="numero_documento" value="<?= esc_attr( $persona->numero_documento) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Año en Curso:</label></th>
                <td><input type="text" name="anio_en_curso" value="<?= esc_attr($alumno_comision->alumno_?->planificacion_?->getAnioLetras() ?? "primer") ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Orientación:</label></th>
                <td><input type="text" name="orientacion" value="<?= esc_attr($alumno_comision->alumno_?->planificacion_?->plan_?->orientacion ?? "Ciencias Sociales") ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Resolución:</label></th>
                <td><input type="text" name="resolucion" value="<?= esc_attr($alumno_comision->alumno_?->planificacion_?->plan_?->resolucion ?? "2993/22") ?>" class="regular-text" required></td>
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
			<tr>
                <th><label>Notas (no se incluyen en la constancia):</label></th>
                <td><textarea readonly name="notas" rows="4" class="large-text"><?= esc_textarea($notas) ?></textarea></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>