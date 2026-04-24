<?
/** @var string $pf_session */
?>
<h1>Informe Alumno <?=$persona->getLabel() ?></h1> 

<form method="POST" action="<?=MAIN_URL?>script/administrar_persona.php">
    <input type="hidden" name="persona_id" value="<?=$persona->id?>" />

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
            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" value="<?=$persona->numero_documento?>" required>
        </div>

        <div class="form-group">
            <label for="cuil1">CUIL (prefijo):</label>
            <input type="number" id="cuil1" name="cuil1" value="<?=$persona->cuil1?>">
        </div>

        <div class="form-group">
            <label for="cuil2">CUIL (sufijo):</label>
            <input type="number" id="cuil2" name="cuil2" value="<?=$persona->cuil2?>">
        </div>

        <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo">
                <option value="" <?= empty($persona->sexo) ? 'selected' : '' ?> disabled hidden>Seleccione...</option>
                <option value="1" <?= $persona->sexo == 1 ? 'selected' : '' ?>>Masculino</option>
                <option value="2" <?= $persona->sexo == 2 ? 'selected' : '' ?>>Femenino</option>
                <option value="3" <?= $persona->sexo == 3 ? 'selected' : '' ?>>No binario</option>
            </select>
        </div>

        <div class="form-group">
            <label for="dia_nacimiento">Día Nacimiento:</label>
            <input type="number" id="dia_nacimiento" name="dia_nacimiento" min="1" max="31" value="<?=$persona->dia_nacimiento?>">
        </div>

        <div class="form-group">
            <label for="mes_nacimiento">Mes Nacimiento:</label>
            <input type="number" id="mes_nacimiento" name="mes_nacimiento" min="1" max="12" value="<?=$persona->mes_nacimiento?>">
        </div>

        <div class="form-group">
            <label for="anio_nacimiento">Año Nacimiento:</label>
            <input type="number" id="anio_nacimiento" name="anio_nacimiento" value="<?=$persona->anio_nacimiento?>">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?=$persona->telefono?>">
        </div>

        <div class="form-group">
            <label for="codigo_area">Código de Área:</label>
            <input type="number" id="codigo_area" name="codigo_area" value="<?=$persona->codigo_area?>">
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
            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" id="nacionalidad" name="nacionalidad" value="<?=$persona->nacionalidad?>">
        </div>

        <div class="form-group">
            <label for="descripcion_domicilio">Descripción Domicilio:</label>
            <input type="text" id="descripcion_domicilio" name="descripcion_domicilio" value="<?=$persona->descripcion_domicilio?>">
        </div>

        <div class="form-group">
            <label for="departamento">Departamento:</label>
            <input type="text" id="departamento" name="departamento" value="<?=$persona->departamento?>">
        </div>

        <div class="form-group">
            <label for="localidad">Localidad:</label>
            <input type="text" id="localidad" name="localidad" value="<?=$persona->localidad?>">
        </div>

        <div class="form-group">
            <label for="partido">Partido:</label>
            <input type="text" id="partido" name="partido" value="<?=$persona->partido?>">
        </div>

    </div>

    <button type="submit" class="submit-btn">Guardar Datos Persona</button>
    
    <br>
    <? if($pf_session): ?>
    <a href="<?=MAIN_URL?>script/pf_modificar_alumno.php?persona_id=<?=$persona->id?>" 
        target="_blank" 
        title="Modificar datos de alumno en programafines"
        onclick="return confirm('¿Desea Modificar datos de alumno en programafines?');">
        <button type="button"> Modificar datos de alumno en Programa Fines </button>
    </a>
    <? endif; ?>
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

@media (max-width: 900px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>