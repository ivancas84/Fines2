<h2>Tomas</h2>

<form method="POST" action="<?=MAIN_URL?>script/modificar_o_eliminar_toma.php">

    <table border="1">
        <tr>
            <th>Fecha</th>
            <th>Disposición</th>
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
                    <select name="curso<?= $i ?>">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($cursos as $curso) : ?>
                            <option value="<?= esc_attr($curso->id); ?>" <? selected($toma->curso, $curso->id); ?>>
                                <?= esc_html($curso->getLabel()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </td>
                    <td>
                        <a href="https://planfines2.com.ar/wp/wp-admin/admin.php?page=fines6-plugin-ap4&persona_id=<?=$toma->docente_?->id?>"><?= esc_html($toma->docente_?->getNombre()); ?></a>
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
                        <button type="submit" onclick="return confirm('Está seguro que desea eliminar?');" class="btn-delete-toma" data-index="<?= $i ?>">                            <span class="dashicons dashicons-trash"></span>
</button>
                        <a 
                            target="_blank"
                            href="https://planfines2.com.ar/v6/script/generar_toma.php?toma_id=<?= $toma->id ?>"
                            onclick="return confirm('¿Está seguro que desea generar toma y enviar Email?');">
                            <span class="dashicons dashicons-id"></span>
                        </a>
                        <a href="<?= esc_url(admin_url('admin.php?page=fines-plugin-ppc3&curso_id=' . $toma->curso_->id)) ?>" title="Cargar Planilla de Calificación" class="button"><span  class="dashicons dashicons-media-spreadsheet"></span></a>
<a href="<?= esc_url('/scripts/cambiar_estado_planilla.php?toma_id=' . $toma?->id . '&estado=entregada') ?>" target="_blank" title="Cambiar entregada" class="button"><span  class="dashicons dashicons-yes"></span></a>
                         <a href="<?= esc_url(admin_url('admin.php?page=fines-plugin-lacu&curso_id=' . $toma->curso_->id))?>" class="button" title="Calificaciones por curso"><span class="dashicons dashicons-paperclip"></span></a>
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