<?php

use Fines7\Core\Plugin;

$boolLabel = static function ($value): string {
    return (int) $value === 1 ? 'Si' : 'No';
};

$sortLink = static function (string $column, string $label) use ($selectedCalendario, $soloAutorizadas, $sort, $order): string {
    $isCurrent = $sort === $column;
    $nextOrder = $isCurrent && $order === 'asc' ? 'desc' : 'asc';
    $indicator = $isCurrent ? ' ' . strtoupper($order) : '';
    $args = [
        'page' => Plugin::COMISIONES_SLUG,
        'calendario' => $selectedCalendario,
        'sort' => $column,
        'order' => $nextOrder,
    ];

    if ($soloAutorizadas) {
        $args['autorizada'] = '1';
    }

    return sprintf(
        '<a href="%s">%s</a>',
        esc_url(add_query_arg($args, admin_url('admin.php'))),
        esc_html($label . $indicator)
    );
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
        <input type="hidden" name="sort" value="<?php echo esc_attr($sort); ?>">
        <input type="hidden" name="order" value="<?php echo esc_attr($order); ?>">

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
                    <th><?php echo $sortLink('nombre', 'Nombre'); ?></th>
                    <th>Domicilio</th>
                    <th><?php echo $sortLink('pfid', 'PFID'); ?></th>
                    <th><?php echo $sortLink('planificacion', 'Planificacion'); ?></th>
                    <th>Autorizada</th>
                    <th><?php echo $sortLink('apertura', 'Apertura'); ?></th>
                    <th><?php echo $sortLink('turno', 'Turno'); ?></th>
                    <th>Cantidad Alumnos</th>
                    <th>Siguiente</th>
                    <th>Referentes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comisiones as $comision) : ?>
                    <?php
                    $planificacionLabel = trim(($comision['planificacion_label'] ?? '') . ' ' . ($comision['plan_label'] ?? ''));
                    $comisionSiguientePfid = (string) ($comision['comision_siguiente_pfid'] ?? '');
                    $comisionSiguienteAnio = (string) ($comision['comision_siguiente_anio'] ?? '');
                    $comisionSiguienteSemestre = (string) ($comision['comision_siguiente_semestre'] ?? '');
                    $comisionSiguienteTramo = $comisionSiguienteAnio !== '' && $comisionSiguienteSemestre !== ''
                        ? $comisionSiguienteAnio . '°' . $comisionSiguienteSemestre . 'C'
                        : '';
                    $comisionSiguienteLabel = trim(implode(' - ', array_filter([
                        $comisionSiguientePfid,
                        $comisionSiguienteTramo,
                    ])));
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
                        <td><?php echo esc_html($comisionSiguienteLabel ?: ''); ?></td>
                        <td><?php echo esc_html($comision['referentes_label'] ?: 'Sin Referentes'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
