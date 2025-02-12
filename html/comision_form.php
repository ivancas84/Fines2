<h1>Editar Persona</h1>
<form id="comision-form" method="POST">
<?php wp_nonce_field("comision_form_action", "comision_form_nonce"); ?>
<input type="hidden" name="comision_id" id="comision_id" value="<?=$comision_id ?? '' ?>" />
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

<input type="text" name="honeypot" style="display: none;">
<p><button type="submit" class='button' />Guardar</button><a href='javascript:history.back();' class='button'>Volver</a>

<div id="form-message"></div>

</form>

<script>
    document.getElementById("persona-form").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let form = this;
        let formData = new FormData(form);
        formData.append("action", "handle_persona_form");
        let messageBox = document.getElementById("form-message");

        fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            messageBox.innerHTML = data.message;
            messageBox.style.color = data.success ? "green" : "red";
            //if (data.success) form.reset();
        })
        .catch(error => console.error("Error:", error));
    });
</script>
