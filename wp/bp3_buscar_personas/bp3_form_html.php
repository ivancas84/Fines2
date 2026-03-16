    <h1>Buscar personas</h1>
    <form method="GET">
        <input type="hidden" name="page" value="<?= FINES_PLUGIN ?>-bp3" />
      
        <label for="busqueda">Buscar:</label>
        <input type="text" name="search" id="search" value="<?= esc_attr($search);?>">

        <input type="submit" name="submit" value="Buscar" class="button button-primary">
    </form>

