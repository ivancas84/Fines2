<h1>Informe <?=$persona->getLabel() ?></h1>

<form  method="POST" action="admin-post.php">
    <?php wp_html_init_form("ad2_persona_admin", "persona_id", $persona->id); ?>


    <div class="form-grid">
        <div class="form-group">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" value="<?=$persona->nombres?>" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?=$persona->apellidos?>">
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento (OJO CON EL FORMATO):</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?=$persona->fecha_nacimiento?->format('Y-m-d')?>">
        </div>

        <div class="form-group">
            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" value="<?=$persona->numero_documento?>" required>
        </div>

        <div class="form-group">
            <label for="cuil">CUIL:</label>
            <input type="text" id="cuil" name="cuil" value="<?=$persona->cuil?>">
        </div>

        <div class="form-group">
            <label for="genero">Género:</label>
            <select id="genero" name="genero">
                <option value="" <?= empty($persona->genero) ? 'selected' : '' ?> disabled hidden>Seleccione...</option>
                <option value="Femenino" <?= $persona->genero === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                <option value="Masculino" <?= $persona->genero === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Otro" <?= $persona->genero === 'Otro' ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?=$persona->telefono?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?=$persona->email?>">
        </div>

        <div class="form-group">
            <label for="email_abc">Email ABC:</label>
            <input type="email" id="email_abc" name="email_abc" value="<?=$persona->email_abc?>">
        </div>

        <div class="form-group">
            <label for="lugar_nacimiento">Lugar de Nacimiento:</label>
            <input type="text" id="lugar_nacimiento" name="lugar_nacimiento" value="<?=$persona->lugar_nacimiento?>">
        </div>

        <div class="form-group">
            <label for="descripcion_domicilio">Descripción Domicilio:</label>
            <input type="text" id="descripcion_domicilio" name="descripcion_domicilio" value="<?=$persona->descripcion_domicilio?>">
        </div>
    </div>

    <button type="submit" class="submit-btn">Guardar Datos Persona</button>

</form>

<style>
    .form-grid {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 15px !important;
        max-width: 1200px !important;
        width: 100% !important;
    }

    .form-group {
        display: flex !important;
        flex-direction: column !important;
    }

    label {
        font-weight: bold !important;
        margin-bottom: 5px !important;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"] {
        padding: 8px !important;
        border: 1px solid #ccc !important;
        border-radius: 5px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .submit-btn {
        display: block !important;
        margin: 20px auto !important;
        padding: 10px 20px !important;
        background-color: #0073aa !important;
        color: white !important;
        font-size: 16px !important;
        border: none !important;
        border-radius: 5px !important;
        cursor: pointer !important;
        transition: 0.3s !important;
    }

    .submit-btn:hover {
        background-color: #005177 !important;
    }

    /* Responsive: fall back to 2 columns on smaller screens */
    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* Responsive: fall back to 1 column on mobile */
    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr !important;
        }
    }

</style>