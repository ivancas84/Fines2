    <h1>Buscar personas</h1>
    <form method="GET">
        <input type="hidden" name="page" value="fines-plugin-bp2" />
      
        <label for="busqueda">Buscar:</label>
        <input type="text" name="search" id="search" value="<?= esc_attr($search);?>">

        <input type="submit" name="submit" value="Buscar" class="button button-primary">
    </form>

