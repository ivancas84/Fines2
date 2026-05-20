<?

use Fines2\Model\Calificacion_;

/**
 * @var array<int, Calificacion_> $calificaciones
 */
?>
<h3>Calificaciones <?=$titulo_calificaciones?></h3>
<p>Los cambios sólo tendrán efecto en las calificaciones aprobadas.</p>
<form method="post" action="<?=MAIN_URL?>script/actualizar_calificaciones.php">    
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Asignatura</th>
                <th>Tramo</th>
                <th>Plan</th>
                <th>Nota Final</th>
                <th>Crec</th>
                <th>Pfid</th>
                <th>Período</th>
                <th>Docente</th>
                <th>Observaciones</th>
                <th>ID curso</th>

            </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($calificaciones); $i++): $cal = $calificaciones[$i]; ?>
            <tr style="<?= $cal->cssBackgroundColor(); ?>">
                <td style="display:none;">
                    <input type="hidden" name="calificacion_id<?=$i?>" value="<?= esc_attr($cal->id) ?>">
                </td>
                <td><?= esc_html($cal->disposicion_->asignatura_->getLabel()) ?></td>
                <td><?= esc_html($cal->disposicion_->planificacion_->getTramo()) ?></td>
                <td><?= esc_html($cal->disposicion_->planificacion_->plan_->getLabel()) ?></td>
                <td>
                    <input type="number" step="1" name="nota_final<?=$i?>" value="<?= round($cal->nota_final) ?>">
                </td>
                <td>
                    <input type="number" step="1" name="crec<?=$i?>" value="<?= round($cal->crec) ?>">
                </td>
                <td><?= esc_html($cal->curso_?->comision_?->pfid ?? "?") ?></td>
                <td><?= esc_html($cal->curso_?->comision_?->calendario_?->getLabel() ?? "?") ?></td>
                <td><?= esc_html($cal->curso_?->toma_activa_?->docente_?->getLabel() ?? "?") ?></td>
                <td>
                    <textarea name="observaciones<?=$i?>"><?=$cal->observaciones?></textarea>
                </td>
                <td>
                    <input type="text" name="curso<?=$i?>" value="<?= $cal->curso ?>">
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
    ✅ Actualizar Calificaciones
</button>

</form>