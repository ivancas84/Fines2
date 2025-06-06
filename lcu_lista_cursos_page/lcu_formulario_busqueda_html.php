   <h1>Búsqueda de Cursos</h1>
    <form method="GET">
        <input type="hidden" name="page" value="fines-plugin-lista-cursos" />
        <label for="calendario">Calendario:</label>
        <select name="calendario" id="calendario">
            <option value="">-- Seleccione --</option>
            <?php foreach ($calendarios as $calendario) : ?>
                <option value="<?php echo esc_attr($calendario->id); ?>" 
                    <?php selected($selected_calendario, $calendario->id); ?>>
                    <?php echo esc_html($calendario->getLabel()); ?>
                </option>
            <?php endforeach; ?>

        </select>


        <input type="submit" name="submit" value="Consultar" class="button button-primary">
    </form>

