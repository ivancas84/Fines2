<h1>Editar Persona</h1>
<form id="dp-persona-form" method="POST">
<?php wp_nonce_field("dp_persona_form_action", "dp_persona_form_nonce"); ?>
<input type="hidden" name="persona_id" id="persona_id" value="<?=$persona_id?>" />
<p><label for="nombres"><strong>Nombres:</strong></label><br />
<input type="text" name="nombres" id="nombres" value="<?=$persona->nombres?>"  /></p>
<p><label for="apellidos"><strong>Apellidos:</strong></label><br />
<input type="text" name="apellidos" id="apellidos" value="<?=$persona->apellidos?>" required /></p>
<p><label for="numero_documento"><strong>DNI:</strong></label><br />
<input type="text" name="numero_documento" id="numero_documento" value="<?=$persona->numero_documento?>" required /></p>
<p><label for="telefono"><strong>Teléfono:</strong></label><br />
<input type="text" name="telefono" id="telefono" value="<?=$persona->telefono?>" /></p>
<input type="text" name="honeypot" style="display: none;">
<p><button type="submit" class='button' />Guardar</button><a href='javascript:history.back();' class='button'>Volver</a>

<div id="form-message"></div>

</form>

<script>
    document.getElementById("dp-persona-form").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let form = this;
        let formData = new FormData(form);
        formData.append("action", "dp_persona_form_handle");
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
