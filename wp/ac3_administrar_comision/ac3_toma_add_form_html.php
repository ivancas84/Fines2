<?php

use Fines2\Model\Comision_;
use Fines2\Model\Curso_;

/** @var Comision_ $comision */
/** @var Curso_[] $cursos */

?>
<h2>Agregar Toma</h2>
<form method="POST" action="<?=MAIN_URL?>script/agregar_toma.php">
    <input type="hidden" name="comision_id" id="comision_id" value="<?= $comision->id ?? '' ?>" />

    <table border="1">
        <tr>
            <th>Fecha</th>
            <th>Curso</th>
            <th>DNI</th>
            <th>Estado</th>
            <th>Tipo Movimiento</th>
            <th>Estado Contralor</th>
        </tr>
            <tr>

                    <td>
                        <input type="date" name="fecha_toma" value="<?= date('Y-m-d') ?>">
                    </td>
                    <td>
                    <select name="curso">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($cursos as $curso) : ?>
                            <option value="<?= esc_attr($curso->id); ?>">
                                <?= esc_html($curso->getLabel()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </td>
                    <td>
                        <input type="text" 
                            name="dni_docente" 
                            placeholder="DNI del docente"
                        >
                    </td>
              
                    <td> 
                        <select name="estado">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($estados as $estado) : ?>
                                <option value="<?= esc_attr($estado); ?>" >
                                    <?= esc_html($estado); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td> 
                        <select name="tipo_movimiento">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($tiposMovimientos as $tipoMovimiento) : ?>
                                <option value="<?= esc_attr($tipoMovimiento); ?>" >
                                    <?= esc_html($tipoMovimiento); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td> 
                        <select name="estado_contralor">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($estadosContralor as $estadoContralor) : ?>
                                <option value="<?= esc_attr($estadoContralor); ?>" >
                                    <?= esc_html($estadoContralor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                   
            </tr>
    </table>

    <button type="submit" id="btn-guardar">Guardar</button>
</form>


