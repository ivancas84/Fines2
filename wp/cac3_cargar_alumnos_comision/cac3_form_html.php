<p>Copiar y pegar desde una nomina en Excel con la estructura definida, modificar encabezados con los siguientes, pueden estar en cualquier orden (* obligatorio):</p>
<ul>
    <li>nombres *</li>
    <li>apellidos</li>
    <li>cuil_dni *</li>
    <li>fecha_nacimiento: Cambiar formato yyyy-mm-dd</li>
    <li>anio_ingreso</li>
    <li>tiene_dni</li>
    <li>tiene_partida</li>
    <li>tiene_certificado</li>
    <li>tiene_constancia</li>
    <li>previas_completas</li>
    <li>observaciones</li>
</ul>
<form method="POST">
    <input type="hidden" name="page" value="<?= FINES_PLUGIN ?>-cac3" />
    <input type="hidden" name="comision_id" value="<?=$comision->id?>" />
    
    <p>
        <label for="data">Datos:</label>
        <textarea name="data" id="data"></textarea>
    </p>
    
    <p>
        <input type="submit" name="submit" value="Procesar" class="button button-primary">
    </p>
    
</form>

