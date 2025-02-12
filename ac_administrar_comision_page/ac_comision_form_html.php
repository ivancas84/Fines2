<h1>Editar Comisión</h1>
<form id="ac-comision-form" method="POST">
    <?php wp_nonce_field("ac_comision_form_action", "ac_comision_form_nonce"); ?>
    <input type="hidden" name="comision_id" id="comision_id" value="<?= $comision_id ?? '' ?>" />

    <!-- Campo Calendario -->
    <p>
        <label for="calendario">Calendario:</label>
        <select name="calendario" id="calendario">
            <option value="">-- Seleccione --</option>
            <?php foreach ($calendarios as $calendario) : ?>
                <option value="<?php echo esc_attr($calendario->id); ?>" 
                    <?php selected($selected_calendario, $calendario->id); ?>>
                    <?php echo esc_html($calendario->anio . "-" . $calendario->semestre . " " . $calendario->descripcion); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <!-- Campo Sede -->
    <p>
        <label for="sede">Sede:</label>
        <select name="sede" id="sede">
            <option value="">-- Seleccione --</option>
            <?php foreach ($sedes as $sede) : ?>
                <option value="<?php echo esc_attr($sede->id); ?>" 
                    <?php selected($selected_sede, $sede->id); ?>>
                    <?php echo esc_html($sede->numero . "-" . $sede->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <!-- Campo Modalidad -->
    <p>
        <label for="modalidad">Modalidad:</label>
        <select name="modalidad">
            <option value="">-- Seleccione --</option>
            <?php foreach ($modalidades as $modalidad) : ?>
                <option value="<?php echo esc_attr($modalidad->id); ?>" 
                    <?php selected($selected_modalidad, $modalidad->id); ?>>
                    <?php echo esc_html($modalidad->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <!-- Campo Planificación -->
    <p>
        <label for="planificacion">Planificación:</label>
        <select name="planificacion">
            <option value="">-- Seleccione --</option>
            <?php foreach ($planificaciones as $planificacion) : ?>
                <option value="<?php echo esc_attr($planificacion->id); ?>" 
                    <?php selected($selected_planificacion, $planificacion->id); ?>>
                    <?php echo esc_html($planificacion->label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <!-- Campo Turno -->
    <p>
        <label for="turno">Turno:</label>
        <select name="turno" id="turno">
            <option value="">-- Seleccione --</option>
            <option value="Mañana" <?= $selected_turno == 'Mañana' ? 'selected' : '' ?>>Mañana</option>
            <option value="Tarde" <?= $selected_turno == 'Tarde' ? 'selected' : '' ?>>Tarde</option>
            <option value="Vespertino" <?= $selected_turno == 'Vespertino' ? 'selected' : '' ?>>Vespertino</option>
        </select>
    </p>

    <!-- Campo División -->
    <p>
        <label for="division">División:</label>
        <input type="text" name="division" id="division" value="<?= $division ?? '' ?>" />
    </p>

    <!-- Campo PFID -->
    <p>
        <label for="pfid">PFID:</label>
        <input type="text" name="pfid" id="pfid" value="<?= $pfid ?? '' ?>" />
    </p>

    <!-- Campo Autorizada -->
    <p>
        <label for="autorizada">Autorizada:</label>
        <input type="checkbox" name="autorizada" id="autorizada" <?= $autorizada ? 'checked' : '' ?> />
    </p>

    <!-- Campo Apertura -->
    <p>
        <label for="apertura">Apertura:</label>
        <input type="checkbox" name="apertura" id="apertura" <?= $apertura ? 'checked' : '' ?> />
    </p>

    <!-- Campo Publicada -->
    <p>
        <label for="publicada">Publicada:</label>
        <input type="checkbox" name="publicada" id="publicada" <?= $publicada ? 'checked' : '' ?> />
    </p>

    <!-- Campo Observaciones -->
    <p>
        <label for="observaciones">Observaciones:</label>
        <textarea name="observaciones" id="observaciones" rows="4"><?= $observaciones ?? '' ?></textarea>
    </p>

    <!-- Botones -->
    <p>
        <button type="submit" class='button'>Guardar</button>
        <a href='javascript:history.back();' class='button'>Volver</a>
    </p>

    <!-- Honeypot -->
    <input type="text" name="honeypot" style="display: none;">

    <div id="ac-form-message"></div>
</form>

<script>
    document.getElementById("ac-comision-form").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let form = this;
        let formData = new FormData(form);
        formData.append("action", "ac_comision_form_handle");
        let messageBox = document.getElementById("ac-form-message");

        fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            messageBox.innerHTML = data.message;
            messageBox.style.color = data.success ? "green" : "red";

            if (data.success && data.comision_id) {
                // Redirigir a la misma página con el ID de la comisión
                window.location.href = "<?php echo admin_url('admin.php?page=fines-plugin-administrar-comision-page&comision_id='); ?>" + data.comision_id;
            }
        })
        .catch(error => console.error("Error:", error));
    });
</script>
