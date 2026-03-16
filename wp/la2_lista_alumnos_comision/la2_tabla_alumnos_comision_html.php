<?php
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;

/** @var AlumnoComision_[] $alumnos_comision */
/** @var Comision_ $comision */

?>

<h2>Cantidad de Alumnos Comision <?=$comision->getLabel() ?>: <?=count($alumnos_comision)?></h2>
<table class="wp-list-table widefat striped">   
    <thead>
        <tr>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Cuil - Dni</th>
            <th>Fecha Nacimiento</th>
            <th>Genero</th>
            <th>Activo</th>

        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnos_comision as $ac): ?>
            <tr>
                <td><?= esc_html($ac->alumno_->persona_?->apellidos ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->nombres ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->getCuilDni() ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->getFechaNacimiento() ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->genero ?? "?"); ?></td>
                <td><?= boolToSiNo($ac->activo); ?></td>

                <td>
                    <a href="<?= esc_url(admin_url('admin.php?page=fines6-plugin-aa4&persona_id=' . $ac->alumno_->persona)) ?>" title="Detalle">
                        <button type="button">
                            <span class="dashicons dashicons-edit"></span>
                        </button>
                    </a>

                    <form method="post" action="<?=MAIN_URL?>script/eliminar_alumno_comision.php" style="display:inline;" 
                        onsubmit="return confirm('Eliminar alumno de la comisión?');">
                        <button type="submit" name="alumno_comision_id" value="<?php echo $ac->id; ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </form>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>