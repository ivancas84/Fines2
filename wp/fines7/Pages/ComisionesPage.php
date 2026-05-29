<?php

namespace Fines7\Pages;

use Fines7\Core\Database;
use Fines7\Repositories\ComisionRepository;

class ComisionesPage
{
    public static function render(): void
    {
        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('No tienes permisos suficientes para acceder a esta pagina.', 'fines7'));
        }

        $calendarios = [];
        $comisiones = [];
        $selectedCalendario = isset($_GET['calendario']) ? sanitize_text_field(wp_unslash($_GET['calendario'])) : '';
        $soloAutorizadas = isset($_GET['autorizada']);
        $error = null;

        try {
            $repository = new ComisionRepository(Database::fines());
            $calendarios = $repository->calendarios();

            if ($selectedCalendario === '' && !empty($calendarios)) {
                $selectedCalendario = (string) $calendarios[0]['id'];
            }

            if ($selectedCalendario !== '') {
                $comisiones = $repository->comisiones($selectedCalendario, $soloAutorizadas);
            }
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'comisiones.php';
    }
}
