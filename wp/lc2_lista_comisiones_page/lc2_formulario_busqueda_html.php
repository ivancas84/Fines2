    <h1>Búsqueda de Comisiones</h1>
    <form method="GET">
        <input type="hidden" name="page" value="fines-plugin-lc2" />
        <label for="calendario">Calendario:</label>
        <select name="calendario" id="calendario">
            <option value="">-- Seleccione --</option>
            <?php foreach ($calendarios as $calendario) : ?>
                <option value="<?php echo esc_attr($calendario->id); ?>" 
                    <?php selected($selected_calendario, $calendario->id); ?>>
                    <?php echo esc_html($calendario->anio . "-" . $calendario->semestre . " " . $calendario->descripcion); ?>
                </option>
            <?php endforeach; ?>

        </select>

        <!-- Checkbox for Autorizada -->
        <label>
            <input type="checkbox" name="autorizada" value="1" <?php checked($filter_autorizada); ?>>
            Solo autorizada
        </label>


        <input type="submit" name="submit" value="Consultar" class="button button-primary">
    </form>

