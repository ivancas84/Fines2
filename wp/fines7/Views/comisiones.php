<?php

use Fines7\Core\Plugin;

$boolLabel = static function ($value): string {
    return (int) $value === 1 ? 'Si' : 'No';
};
?>
<div class="wrap fines7-wrap">
    <h1>Comisiones</h1>

    <?php if (!empty($error)) : ?>
        <div class="notice notice-error">
            <p><?php echo esc_html('Fines7 no pudo acceder a la base Fines: ' . $error); ?></p>
        </div>
    <?php endif; ?>

    <form method="get" class="fines7-filters">
        <input type="hidden" name="page" value="<?php echo esc_attr(Plugin::COMISIONES_SLUG); ?>">

        <label for="fines7-calendario">Calendario:</label>
        <select name="calendario" id="fines7-calendario">
            <?php if (empty($calendarios)) : ?>
                <option value="">Sin calendarios disponibles</option>
            <?php else : ?>
                <?php foreach ($calendarios as $calendario) : ?>
                    <?php
                    $label = trim(($calendario['anio'] ?? '') . '-' . ($calendario['semestre'] ?? '') . ' ' . ($calendario['descripcion'] ?? ''));
                    ?>
                    <option value="<?php echo esc_attr($calendario['id']); ?>" <?php selected($selectedCalendario, $calendario['id']); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label>
            <input type="checkbox" name="autorizada" value="1" <?php checked($soloAutorizadas); ?>>
            Solo autorizada
        </label>

        <input type="submit" value="Consultar" class="button button-primary">
    </form>

    <h2>Comisiones Consultadas <?php echo esc_html((string) count($comisiones)); ?></h2>

    <?php if (empty($error) && empty($comisiones)) : ?>
        <p>No se encontraron comisiones para este calendario.</p>
    <?php endif; ?>

    <?php if (!empty($comisiones)) : ?>
        <table class="wp-list-table widefat striped fines7-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Domicilio</th>
                    <th>PFID</th>
                    <th>Planificacion</th>
                    <th>Autorizada</th>
                    <th>Apertura</th>
                    <th>Turno</th>
                    <th>Cantidad Alumnos</th>
                    <th>Siguiente</th>
                    <th>Referentes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comisiones as $comision) : ?>
                    <?php
                    $planificacionLabel = trim(($comision['planificacion_label'] ?? '') . ' ' . ($comision['plan_label'] ?? ''));
                    ?>
                    <tr>
                        <td><?php echo esc_html($comision['sede_nombre'] ?: '?'); ?></td>
                        <td><?php echo esc_html($comision['domicilio_label'] ?: '?'); ?></td>
                        <td><?php echo esc_html($comision['pfid'] ?: ''); ?></td>
                        <td><?php echo esc_html($planificacionLabel ?: '?'); ?></td>
                        <td><?php echo esc_html($boolLabel($comision['autorizada'])); ?></td>
                        <td><?php echo esc_html($boolLabel($comision['apertura'])); ?></td>
                        <td><?php echo esc_html($comision['turno'] ?: ''); ?></td>
                        <td>
                            <?php
                            echo esc_html(
                                (string) $comision['cantidad_alumnos']
                                . '/'
                                . (string) $comision['cantidad_alumnos_activos']
                            );
                            ?>
                        </td>
                        <td><?php echo esc_html($comision['comision_siguiente'] ?: ''); ?></td>
                        <td><?php echo esc_html($comision['referentes_label'] ?: 'Sin Referentes'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
