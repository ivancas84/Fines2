<h2>Tomas</h2>

<table border="1">
    <tr>
        <th>Fecha</th>
        <th>Disposición</th>
        <th>Estado</th>
        <th>Tipo Movimiento</th>
        <th>Estado Contralor</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($tomas as $toma): ?>
        <tr>
            <form method="POST" action="<?=MAIN_URL?>script/update_entity.php">
                <input type="text" name="honeypot" style="display: none;">
                <input type="hidden" name="id" value="<?= esc_attr($toma->id) ?>">
                <input type="hidden" name="entity_name" value="toma">
                <input type="hidden" name="persona_id" value="<?= esc_attr($persona->id) ?>">

                <td>
                    <input 
                        type="date" 
                        name="fecha_toma" 
                        value="<?= $toma->fecha_toma?->format('Y-m-d') ?>"
                    >
                </td>

                <td>
                    <?= esc_html($toma->curso_?->getLabel() ?? "?"); ?>
                </td>

                <td>
                    <select name="estado">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= esc_attr($estado); ?>" <?= selected($toma->estado, $estado, false); ?>>
                                <?= esc_html($estado); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <select name="tipo_movimiento">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($tiposMovimientos as $tipoMovimiento): ?>
                            <option value="<?= esc_attr($tipoMovimiento); ?>" <?= selected($toma->tipo_movimiento, $tipoMovimiento, false); ?>>
                                <?= esc_html($tipoMovimiento); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <select name="estado_contralor">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($estadosContralor as $estadoContralor): ?>
                            <option value="<?= esc_attr($estadoContralor); ?>" <?= selected($toma->estado_contralor, $estadoContralor, false); ?>>
                                <?= esc_html($estadoContralor); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                    <td>Toma <?= esc_html($toma?->id); ?><br>
                    Curso <?= esc_html($toma?->curso); ?><br>
                    Comisión <?= esc_html($toma?->curso_?->comision); ?><br>
                    Sede <?= esc_html($toma?->curso_?->comision_?->sede); ?>
                </td>
                <td style="white-space: nowrap;">
                    <button 
                        type="submit" 
                        name="action" 
                        value="ad2_toma_update"
                        class="button"
                        title="Actualizar toma"
                    >
                        <span class="dashicons dashicons-edit"></span>

                    </button>

                    <button 
                        type="submit" 
                        name="action" 
                        value="ad2_toma_delete"
                        class="button button-secondary" 
                        title="Eliminar toma"

                        onclick="return confirm('¿Está seguro que desea eliminar esta toma?');"
                    >
                        <span class="dashicons dashicons-table-row-delete"></span>

                    </button>

                    <a 
                      title="Generar toma y enviar Email"
                      class="button"
                        target="_blank"
                        href="<?=MAIN_URL?>script/generar_toma.php?toma_id=<?= $toma->id ?>"
                        onclick="return confirm('¿Está seguro que desea generar toma y enviar Email?');">
                        <span class="dashicons dashicons-text-page"></span>
                    </a>

                    <a href="<?= esc_url(admin_url('admin.php?page='.FINES_PLUGIN.'-ppc3&curso_id=' . $toma->curso_->id)) ?>" 
                        title="Cargar Planilla de Calificación" 
                        class="button">
                        <span class="dashicons dashicons-media-spreadsheet"></span>
                    </a>

                    <a href="<?= esc_url(MAIN_URL.'/script/cambiar_estado_planilla.php?toma_id=' . $toma->id . '&estado=entregada') ?>" 
                        target="_blank" 
                        title="Cambiar entregada" 
                        class="button">
                        <span class="dashicons dashicons-yes"></span>
                    </a>

                    <a href="<?= esc_url(admin_url('admin.php?page=fines-plugin-lacu&curso_id=' . $toma->curso_->id)) ?>" 
                        class="button" 
                        title="Calificaciones por curso">
                        <span class="dashicons dashicons-paperclip"></span>
                    </a>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>
