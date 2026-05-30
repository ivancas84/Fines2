<?php

use Fines7\Core\Plugin;

?>
<div class="wrap fines7-wrap">
    <h1>Buscar Personas</h1>

    <?php if (!empty($error)) : ?>
        <div class="notice notice-error">
            <p><?php echo esc_html('Fines7 no pudo acceder a la base Fines: ' . $error); ?></p>
        </div>
    <?php endif; ?>

    <form method="get" class="fines7-filters">
        <input type="hidden" name="page" value="<?php echo esc_attr(Plugin::PERSONAS_SLUG); ?>">

        <label for="fines7-personas-search">Buscar:</label>
        <input
            type="search"
            name="search"
            id="fines7-personas-search"
            value="<?php echo esc_attr($search); ?>"
            class="regular-text"
        >

        <input type="submit" name="submit" value="Buscar" class="button button-primary">
    </form>

    <?php if ($submitted && $search === '') : ?>
        <p>Ingresa un texto para buscar personas.</p>
    <?php endif; ?>

    <?php if ($submitted && $search !== '' && empty($error)) : ?>
        <h2>Personas encontradas <?php echo esc_html((string) count($personas)); ?></h2>

        <?php if (empty($personas)) : ?>
            <p>No se encontraron personas.</p>
        <?php else : ?>
            <table class="wp-list-table widefat striped fines7-table">
                <thead>
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>DNI</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Email ABC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personas as $persona) : ?>
                        <tr>
                            <td><?php echo esc_html($persona['apellidos'] ?: ''); ?></td>
                            <td><?php echo esc_html($persona['nombres'] ?: ''); ?></td>
                            <td><?php echo esc_html($persona['numero_documento'] ?: ''); ?></td>
                            <td><?php echo esc_html($persona['telefono'] ?: ''); ?></td>
                            <td><?php echo esc_html($persona['email'] ?: ''); ?></td>
                            <td><?php echo esc_html($persona['email_abc'] ?: ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
