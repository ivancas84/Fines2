<?php

use Fines2\Model\Alumno_;

/** @var Alumno_ $alumno */
/** @var string[] $estados */


?>
<h2>Agregar Comision a Alumno</h2>
<form method="POST" action="<?=MAIN_URL?>script/agregar_alumno_comision.php">
    <input type="hidden" name="alumno_id" value="<?= esc_attr($alumno->id) ?>">

    <table border="1">
        <tr>
            <th>Id comision</th>
            <th>Estado</th>
           
        </tr>
        <tr>

                    <td>
                        <input type="comision" name="comision_id">
                    </td>
                    <td>
                         <select name="estado" >
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($estados as $estado) : ?>
                            <option value="<?php echo esc_attr($estado); ?>">
                                <?php echo esc_html($estado); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>    
                    </td>
        </tr>
    </table>

    <button type="submit" id="btn-guardar">Guardar</button>
</form>


