<h3>Comisiones</h3>
<form method="post" action="admin-post.php">    
    <?php wp_html_init_form("ap3_comisiones_update", "persona_id", $persona->id); ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Sede</th>
                <th>Pfid</th>
                <th>Periodo</th>
                <th>Tramo</th>
                <th>Estado</th>
                <th>Activo</th>
                <th>Opciones</th>

            </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($alumno_comisiones); $i++):  $ac = $alumno_comisiones[$i]; ?>
            <tr>
                <input type="hidden" name="comision_id<?=$i?>" value="<?= esc_attr($ac->id) ?>">

                <td><?= esc_html($ac->comision_->sede_->getLabel()) ?></td>
                <td><?= esc_html($ac->comision_->pfid) ?></td>
                <td><?= esc_html($ac->comision_->calendario_->getLabel()) ?></td>
                <td><?= esc_html($ac->comision_->planificacion_->getLabel()) ?></td>
                <td>
                    <select name="estado<?=$i?>" >
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($estados as $estado) : ?>
                            <option value="<?php echo esc_attr($estado); ?>" 
                                <?php selected($ac->estado, $estado); ?>>
                                <?php echo esc_html($estado); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>    
                </td>
                <td>
                    <input type="hidden" name="activo<?=$i?>" value="false">
                    <input type="checkbox" name="activo<?=$i?>" value="true" <?= $ac->activo ? 'checked' : '' ?>>
                </td>
                <td>
                    <button type="submit" onclick="return confirm('Está seguro que desea eliminar?');" class="btn-delete-comision" data-index="<?= $i ?>">Eliminar</button>
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>

    <button type="submit" style="
    margin-top: 15px;
    background-color: #0073aa;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
"
    onmouseover="this.style.backgroundColor='#005177'"
    onmouseout="this.style.backgroundColor='#0073aa'"
>
    ✅ Actualizar Comisiones
</button>

</form>