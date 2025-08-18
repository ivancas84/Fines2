<h2>Tomas</h2>

<form method="POST" action="admin-post.php">
    <?php wp_html_init_form("ac2_tomas_modify_delete", "comision_id", $comision->id); ?>

    <table border="1">
        <tr>
            <th>Fecha</th>
            <th>Docente</th>
            <th>DNI</th>
            <th>Contacto</th>
            <th>Estado</th>
            <th>Tipo Movimiento</th>
            <th>Estado Contralor</th>
            <th>Acciones</th>
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
                        <?= esc_html($toma->docente_?->getNombre()); ?>
                    </td>
                    <td>
                        <input type="text" 
                            name="dni_docente<?= $i ?>" 
                            value="<?= esc_attr($toma->docente_?->numero_documento) ?>" 
                            placeholder="DNI del docente"
                        >
                    </td>
                    <td>
                        <?= esc_html($toma->docente_?->telefono) ?? "?"; ?></br>
                        <?= esc_html($toma->docente_?->email) ?? "?"; ?></br>
                        <?= esc_html($toma->docente_?->email_abc) ?? "?"; ?>
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
                        <select name="estado_contralor<?=$i?>">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($estadosContralor as $estadoContralor) : ?>
                                <option value="<?= esc_attr($estadoContralor); ?>" <?= selected($toma->estado_contralor, $estadoContralor); ?>>
                                    <?= esc_html($estadoContralor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button type="submit" onclick="return confirm('Está seguro que desea eliminar?');" class="btn-delete-toma" data-index="<?= $i ?>">Eliminar</button>
                        <a class="button-link"
                            target="_blank"
                            href="https://planfines2.com.ar/scripts/generar_toma.php?toma_id=<?= $toma->id ?>"
                            onclick="return confirm('¿Está seguro que desea generar toma y enviar Email?');">
                            Generar Toma
                            </a>
                    </td>
            </tr>
        <?php endfor; ?>
    </table>

    <input type="hidden" name="delete_toma_index" id="delete_toma_index">

    <button type="submit" id="btn-guardar">Guardar</button>
</form>

<script>
document.querySelectorAll('.btn-delete-toma').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('delete_toma_index').value = this.dataset.index;
    });
});

// Limpiar el campo delete_toma_index cuando se hace clic en Guardar
document.getElementById('btn-guardar').addEventListener('click', function() {
    document.getElementById('delete_toma_index').value = '';
});

// También limpiar el campo al cargar la página (por si queda en caché)
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('delete_toma_index').value = '';
});
</script>