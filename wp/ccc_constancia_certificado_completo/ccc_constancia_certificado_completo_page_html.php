<div class="wrap">
    <h1>Formulario de Datos del Alumno</h1>
    <form method="POST" action="https://planfines2.com.ar/scripts/constancia_certificado_completo.php">
        <table class="form-table">
		<tr>
                <th><label>Nombres:</label></th>
                <td><input type="text" name="nombres" value="<?= esc_attr($alumno->persona_?->nombres) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Apellidos:</label></th>
                <td><input type="text" name="apellidos" value="<?= esc_attr($alumno->persona_?->apellidos) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Número de Documento:</label></th>
                <td><input type="text" name="numero_documento" value="<?= esc_attr($alumno->persona_?->numero_documento ?? "") ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Año en Curso:</label></th>
                <td><input type="text" name="anio" value="<?= esc_attr($anio) ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Orientación:</label></th>
                <td><input type="text" name="orientacion" value="<?= esc_attr($alumno_comision->comision_?->planificacion_?->plan_?->orientacion ?? "") ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Resolución:</label></th>
                <td><input type="text" name="resolucion" value="<?= esc_attr($alumno_comision->comision_?->planificacion_?->plan_?->resolucion ?? "") ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>Fecha:</label></th>
                <td><input type="text" name="fecha" value="<?= esc_attr($fecha) ?>" class="regular-text" readonly></td>
            </tr>
            <tr>
                <th><label>Presentado a:</label></th>
                <td><input type="text" name="presentado" value="<?= esc_attr($presentado) ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Observaciones:</label></th>
                <td><textarea name="observaciones" rows="4" class="large-text"><?= esc_textarea($observaciones) ?></textarea></td>
            </tr>
			<tr>
                <th><label>Notas (no se incluyen en la constancia):</label></th>
                <td><textarea readonly name="notas" rows="4" class="large-text"><?= esc_textarea($notas) ?></textarea></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>