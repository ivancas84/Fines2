<?php


function fines_plugin_persona_form_page() {
    if (!isset($_GET['persona_id']) || empty($_GET['persona_id'])) {
        echo "<p>Error: No se especificó el ID de la persona.</p>";
        return;
    }

    $persona_id = $_GET['persona_id'];
    $wpdb = fines_plugin_db_connection();

    // Fetch student details from the database
    $persona = $wpdb->get_row(
        $wpdb->prepare("SELECT persona.nombres, persona.apellidos, persona.numero_documento, persona.telefono
                        FROM persona                        
                        WHERE persona.id = '{$persona_id}'")
    );

    $alumno = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM alumno WHERE persona = '$persona_id'")
    );
?>

<h1>Editar Persona</h1>
<form id="persona-form" method="POST">
<?php wp_nonce_field("custom_form_action", "custom_form_nonce"); ?>
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


 <?php if ($alumno): ?>

        <h1>Editar Alumno</h1>
        <form id="alumno-form" method="POST">
            <?php wp_nonce_field("custom_alumno_form_action", "custom_alumno_form_nonce"); ?>
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
    <?php else: ?>
        <p>No se encontró un alumno asociado con esta persona.</p>
    <?php endif;



    if ($alumno) {
        $calificaciones = $wpdb->get_results(
            $wpdb->prepare("
                SELECT calificacion.nota_final, calificacion.crec, planificacion.anio, planificacion.semestre, plan.orientacion, asignatura.nombre FROM calificacion 
                INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
                INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
                INNER JOIN plan ON (planificacion.plan = plan.id)
                INNER JOIN asignatura ON (disposicion.asignatura = asignatura.id)
                WHERE alumno = '$alumno->id'
                ORDER BY planificacion.anio, planificacion.semestre
				LIMIT 100;
            ")
        );

        if ($calificaciones) {
            echo "<h2>Calificaciones</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Asignatura</th>
                    <th>Año</th>
                    <th>Semestre</th>
                    <th>Nota Final</th>
                    <th>CREC</th>
                  </tr>";

            foreach ($calificaciones as $cal) {
                echo "<tr>
                        <td>{$cal->nombre}</td>
                        <td>{$cal->anio}</td>
                        <td>{$cal->semestre}</td>
                        <td>{$cal->nota_final}</td>
                        <td>{$cal->crec}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
    } else {
        echo "<p>No se encontró un alumno asociado con esta persona.</p>";
    }
	
}

