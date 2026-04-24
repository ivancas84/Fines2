<?php
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;

/** @var AlumnoComision_[] $alumnos_comision */
/** @var Comision_ $comision */
/** @var string $pf_session //Sesion de pf */

?>

<h2>Cantidad de Alumnos Comision <?=$comision->getLabel() ?>: <?=count($alumnos_comision)?></h2>
<table class="wp-list-table widefat striped">   
    <thead>
        <tr>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Cuil1</th>
            <th>Cuil - Dni</th>
            <th>Cuil2</th>
            <th>Cod</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Dia Nac</th>
            <th>Mes Nac</th>
            <th>Año Nac</th>
            <th>Sexo</th>
            <th>Activo</th>

        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnos_comision as $ac): ?>
            <tr>
                <td><?= esc_html($ac->alumno_->persona_?->apellidos ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->nombres ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->cuil1 ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->getCuilDni() ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->cuil2 ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->codigo_area ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->telefono ?? "?"); ?></td>


                <td><?= esc_html($ac->alumno_->persona_?->email ?? "?"); ?></td>

                <td><?= esc_html($ac->alumno_->persona_?->dia_nacimiento ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->mes_nacimiento ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->anio_nacimiento ?? "?"); ?></td>
                <td><?= esc_html($ac->alumno_->persona_?->getSexo() ?? "?"); ?></td>
                <td><?= boolToSiNo($ac->activo); ?></td>

                <td>
                    
                    
                    <a href="<?= esc_url(admin_url('admin.php?page=fines6-plugin-aa4&persona_id=' . $ac->alumno_->persona)) ?>" title="Detalle">
                        <button type="button">
                            <span class="dashicons dashicons-edit"></span>
                        </button>
                    </a>

                    <form method="post" action="<?=MAIN_URL?>script/eliminar_alumno_comision.php" style="display:inline;" 
                        onsubmit="return confirm('Eliminar alumno de la comisión?');">
                        <button type="submit" name="alumno_comision_id" value="<?php echo esc_attr($ac->id); ?>" title="Eliminar">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </form>

                    <? if(!empty($pf_session)): ?>
                        
                        <form method="post" action="<?=MAIN_URL?>script/pf_agregar_alumno.php" style="display:inline;" 
                            onsubmit="return confirm('Desea agregar alumno a la comisión de programafines?');">
                            <input type="hidden" name="nombres" value="<?= esc_attr($ac->alumno_->persona_?->nombres) ?>"/>
                            <input type="hidden" name="apellidos" value="<?= esc_attr($ac->alumno_->persona_?->apellidos) ?>"/>
                            <input type="hidden" name="cuil1" value="<?= esc_attr($ac->alumno_->persona_?->cuil1) ?>"/>
                            <input type="hidden" name="numero_documento" value="<?= esc_attr($ac->alumno_->persona_?->numero_documento) ?>"/>
                            <input type="hidden" name="cuil2" value="<?= esc_attr($ac->alumno_->persona_?->cuil2) ?>"/>
                            <input type="hidden" name="alumno_comision_id" value="<?= esc_attr($ac->id) ?>"/>
                            <button type="submit" title="Agregar a Programafines" >
                                <span class="dashicons dashicons-plus"></span>
                            </button>
                        </form>
                    <? endif; ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>