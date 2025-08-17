<h2>Tomas</h2>

<form method="POST" action="admin-post.php">
    <?php wp_html_init_form("ac2_tomas_modify_delete", "comision_id", $comision->id); ?>

    <table border="1">
        <tr>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Tipo Movimiento</th>
        </tr>
        <?php for($i = 0; $i < count($tomas); $i++): $toma = $tomas[$i]; ?>
            <tr>
                    <input type="hidden" name="toma_id<?=$i?>" value="<?= esc_attr($toma->id) ?>">

                    <td>
                        <input type="date" 
                            name="fecha_toma<?= $i ?>" 
                            value="<?= $toma->fecha_toma?->format('Y-m-d') ?>" 
                        >
                    </td>
                    <td> 
                        <select name="estado<?=$i?>">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($estados as $estado) : ?>
                                <option value="<?= esc_attr($estado); ?>" <? selected($toma->estado, $estado); ?>>
                                    <?= esc_html($estado); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td> 
                        <select name="tipo_movimiento<?=$i?>">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($tiposMovimientos as $tipoMovimiento) : ?>
                                <option value="<?= esc_attr($tipoMovimiento); ?>" <?= selected($toma->tipo_movimiento, $tipoMovimiento); ?>>
                                    <?= esc_html($tipoMovimiento); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn-delete-toma" data-index="<?= $i ?>">Eliminar</button>
                    </td>
            </tr>
        <?php endfor; ?>
    </table>

    <input type="hidden" name="delete_toma_index" id="delete_toma_index">

    <button type="submit">Guardar</button>
</form>

<script>
document.querySelectorAll('.btn-delete-toma').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('delete_toma_index').value = this.dataset.index;
    });
});
</script>