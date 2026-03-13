<h1>Procesar Docentes PF</h1>
<p>Descargar xlsx de docentes de programa fines</p>
<p>El script obtiene por cada fila del xslx Apellido, Nombre, DNI, Direccion, Localidad, FechaNac, TelCelular, Email e inserta o actualiza los datos en el sistema</p>
<p>Las tomas del CENS 462 son agregadas si no existen en el sistema. Si existe compara que sea el mismo docente y tira una advertencia si cambio</p>
<p><strong>Se recomienda eliminar las últimas columnas del XLSX (Dirección y Localidad) correspondientes al CENS para no confundir el armado de los arrays</strong></p>
<form method="POST">
    <label for="busqueda">Datos:</label>
    <textarea name="data" id="data" value="<?= esc_attr($data);?>"></textarea>

    <input type="submit" name="submit" value="Consultar" class="button button-primary">
</form>

