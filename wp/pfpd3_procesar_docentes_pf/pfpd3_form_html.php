<h1>Procesar Docentes PF</h1>
<p>Descargar xlsx de docentes de programa fines</p>
<form method="POST">
    <input type="hidden" name="page" value="fines-plugin-procesar-docentes-pf" />
    
    <label for="busqueda">Datos:</label>
    <textarea name="data" id="data" value="<?= esc_attr($data);?>"></textarea>

    <input type="submit" name="submit" value="Consultar" class="button button-primary">
</form>

