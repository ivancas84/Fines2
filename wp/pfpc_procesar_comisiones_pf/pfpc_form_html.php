<h1>Procesar Comisiones PF</h1>
<p>Copiar y pegar info del informe global</p>
<form method="POST">
    <input type="hidden" name="page" value="fines-plugin-pfpc" />
    
    <label for="busqueda">Datos:</label>
    <textarea name="data" id="data" value="<?= esc_attr($data);?>"></textarea>

    <input type="submit" name="submit" value="Consultar" class="button button-primary">
</form>

