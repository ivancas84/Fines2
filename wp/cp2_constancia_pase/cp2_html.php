<div class="wrap">
    <h1>Constancia de Pase</h1>
    <form method="POST" action="https://planfines2.com.ar/scripts/constancia_pase.php">
        <table class="form-table">
            <input type="hidden" name="alumno_id" value="<?= esc_attr($alumno->id) ?>">
		    <tr>
                <th><label>Alumno:</label></th>
                <td><?= $alumno->persona_->getLabel() ?></td>
            </tr>
            <tr>
                <th><label>Años Cursados:</label></th>
                <td><?= $aniosCursados ?></td>
            </tr>
            <tr>
                <th><label>Orientación / Resolución:</label></th>
                <td><?= $alumno->plan_->orientacion ?> / <?= $alumno->plan_->resolucion ?></td>
            </tr>
            <tr>
                <th><label>Fecha:</label></th>
                <td><?= $fecha ?></td>
            </tr>
            
            
            <tr>
                <th><label>Aprobadas / Desaprobadas:</label></th>
                <td><?= $alumno->CalificacionAprobada_Count ?> / <?= $alumno->CalificacionDesaprobada_Count ?></td>
            </tr>

            <tr>
                <th><label>Presentado a:</label></th>
                <td><input type="text" name="presentacion" value="Quién Corresponda" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Observaciones:</label></th>
                <td><textarea name="observaciones" rows="4" class="large-text"></textarea></td>
            </tr>
			
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>