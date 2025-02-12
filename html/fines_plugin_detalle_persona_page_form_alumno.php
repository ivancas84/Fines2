<h1>Editar Alumno</h1>
        <form id="alumno-form" method="POST">
            <?php wp_nonce_field("alumno_form_action", "alumno_form_nonce"); ?>
            <input type="hidden" name="alumno_id" id="alumno_id" value="<?=$alumno->id?>" />
            
            <p><label for="anio_ingreso"><strong>Año de Ingreso:</strong></label><br />
            <input type="text" name="anio_ingreso" id="anio_ingreso" value="<?=$alumno->anio_ingreso?>" /></p>

            <p><label for="estado_inscripcion"><strong>Estado de Inscripción:</strong></label><br />
            <input type="text" name="estado_inscripcion" id="estado_inscripcion" value="<?=$alumno->estado_inscripcion?>" /></p>

            <p><label for="plan"><strong>Plan:</strong></label><br />
            <input type="text" name="plan" id="plan" value="<?=$alumno->plan?>" /></p>

            <p><label for="fecha_titulacion"><strong>Fecha de Titulación:</strong></label><br />
            <input type="date" name="fecha_titulacion" id="fecha_titulacion" value="<?=$alumno->fecha_titulacion?>" /></p>

            <p><label for="observaciones"><strong>Observaciones:</strong></label><br />
            <textarea name="observaciones" id="observaciones"><?=$alumno->observaciones?></textarea></p>

            <p><button type="submit" class='button'>Guardar Alumno</button></p>
            <div id="alumno-form-message"></div>
        </form>

        <script>
            document.getElementById("alumno-form").addEventListener("submit", function(event) {
                event.preventDefault();
                
                let form = this;
                let formData = new FormData(form);
                formData.append("action", "handle_alumno_form");
                let messageBox = document.getElementById("alumno-form-message");

                fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    messageBox.innerHTML = data.message;
                    messageBox.style.color = data.success ? "green" : "red";
                })
                .catch(error => console.error("Error:", error));
            });
        </script>
