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

        <!-- Select for Order -->
        <label for="order_by">Ordenar por:</label>
        <select name="order_by" id="order_by">
            <option value="tramo" <?php selected($selected_order, 'tramo'); ?>>Tramo</option>
            <option value="nombre" <?php selected($selected_order, 'nombre'); ?>>Nombre</option>
            <option value="orientacion" <?php selected($selected_order, 'orientacion'); ?>>Orientación</option>
            <option value="apertura" <?php selected($selected_order, 'apertura'); ?>>Apertura</option>

        </select>

        <input type="submit" name="submit" value="Consultar" class="button button-primary">
    </form>

