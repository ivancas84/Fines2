<form id="ap3-alumno-admin" action="admin-post.php" method="POST">
    <?php wp_html_init_form("ap3_alumno_admin", "persona_id", $persona->id); ?>
    <div class="form-grid">
        <div class="form-group">
            
            <label for="estado_inscripcion">Estado de Inscripción:</label>
            <select name="estado_inscripcion" id="estado_inscripcion">
                <option value="">-- Seleccione --</option>
                <?php foreach ($estados_inscripcion as $estado) : ?>
                    <option value="<?php echo esc_attr($estado); ?>" 
                        <?php selected($alumno->estado_inscripcion, $estado); ?>>
                        <?php echo esc_html($estado); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="plan">Planes:</label>
            <select name="plan" id="plan">
                <option value="">-- Seleccione --</option>
                <?php foreach ($planes as $plan) : ?>
                    <option value="<?php echo esc_attr($plan->id); ?>" 
                        <?php selected($alumno->plan, $plan->id); ?>>
                        <?php echo esc_html($plan->orientacion . "-" . $plan->resolucion); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="anio_ingreso">Año de Ingreso:</label>
            <select id="anio_ingreso" name="anio_ingreso">
                <option value="1" <?= $alumno->anio_ingreso == 1 ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $alumno->anio_ingreso == 2 ? 'selected' : '' ?>>2</option>
                <option value="3" <?= $alumno->anio_ingreso == 3 ? 'selected' : '' ?>>3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="semestre_ingreso">Semestre de Ingreso:</label>
            <select id="semestre_ingreso" name="semestre_ingreso">
                <option value="1" <?= $alumno->semestre_ingreso == 1 ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $alumno->semestre_ingreso == 2 ? 'selected' : '' ?>>2</option>
            </select>
        </div>
        

    <div class="form-group">
        <label for="anio_inscripcion">Año de Inscripción:</label>
        <input type="number" id="anio_inscripcion" name="anio_inscripcion" value="<?=$alumno->anio_inscripcion?>">
    </div>

    <div class="form-group">
        <label for="semestre_inscripcion">Semestre de Inscripción:</label>
        <input type="number" id="semestre_inscripcion" name="semestre_inscripcion" value="<?=$alumno->semestre_inscripcion?>">
    </div>

    <div class="form-group">
        <label for="establecimiento_inscripcion">Establecimiento:</label>
        <input type="text" id="establecimiento_inscripcion" name="establecimiento_inscripcion" value="<?=$alumno->establecimiento_inscripcion?>">
    </div>

    <div class="form-group">
        <label for="fecha_titulacion">Fecha de Titulación:</label>
        <input type="date" id="fecha_titulacion" name="fecha_titulacion" value="<?=$alumno->fecha_titulacion?->format('Y-m-d')?>">
    </div>

    <div class="form-group" style="grid-column: span 2;">
        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones"><?=$alumno->observaciones?></textarea>
    </div>

</div>
    <h3>Legajo</h3>
    <p> El legajo se actualiza desde el drive según la estructura establecida.</p>
    <div class="checkbox-grid">
        <label><input type="checkbox" name="tiene_dni" value="1" <?= $alumno->tiene_dni ? 'checked' : '' ?>> Tiene DNI</label>
        <label><input type="checkbox" name="tiene_constancia" value="1" <?= $alumno->tiene_constancia ? 'checked' : '' ?>> Tiene Constancia</label>
        <label><input type="checkbox" name="tiene_certificado" value="1" <?= $alumno->tiene_certificado ? 'checked' : '' ?>> Tiene Certificado</label>
        <label><input type="checkbox" name="previas_completas" value="1" <?= $alumno->previas_completas ? 'checked' : '' ?>> Previas Completas</label>
        <label><input type="checkbox" name="tiene_partida" value="1" <?= $alumno->tiene_partida ? 'checked' : '' ?>> Tiene Partida</label>
        <label><input type="checkbox" name="confirmado_direccion" value="1" <?= $alumno->confirmado_direccion ? 'checked' : '' ?>> Confirmado por Dirección</label>
    </div>

    <input type="hidden" name="alumno_id" value="<?= esc_attr($alumno->id); ?>"/>

    <button type="submit" class="submit-btn">Guardar Cambios</button>

</form>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        max-width: 800px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    input, select, textarea {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .submit-btn {
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #0073aa;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .submit-btn:hover {
        background-color: #005177;
    }

    #alumno-form-message {
        margin-top: 10px;
        text-align: center;
        font-weight: bold;
    }
</style>

