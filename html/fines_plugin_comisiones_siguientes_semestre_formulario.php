    <h1>Seleccionar Calendario</h1>
    <h2>Se generarán las comisiones siguientes del calendario seleccionado, si no existen</h2>
    <form method="GET">
        <input type="hidden" name="page" value="fines-plugin-comisiones-siguientes-semestre" />
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
		<select name="calendario_siguiente" id="calendario_siguiente">
            <option value="">-- Seleccione --</option>
            <?php foreach ($calendarios as $calendario) : ?>
                <option value="<?php echo esc_attr($calendario->id); ?>" 
                    <?php selected($selected_calendario_siguiente, $calendario->id); ?>>
                    <?php echo esc_html($calendario->anio . "-" . $calendario->semestre . " " . $calendario->descripcion); ?>
                </option>
            <?php endforeach; ?>

        </select>

        <input type="submit" name="submit" value="Consultar" class="button button-primary">
    </form>

