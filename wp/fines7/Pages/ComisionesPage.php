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
        $sort = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'pfid';
        $order = isset($_GET['order']) ? strtolower(sanitize_key(wp_unslash($_GET['order']))) : 'asc';
        $allowedSorts = ['nombre', 'pfid', 'planificacion', 'apertura', 'turno'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'pfid';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }
        $error = null;

        try {
            $repository = new ComisionRepository(Database::fines());
            $calendarios = $repository->calendarios();

            if ($selectedCalendario === '' && !empty($calendarios)) {
                $selectedCalendario = (string) $calendarios[0]['id'];
            }

            if ($selectedCalendario !== '') {
                $comisiones = $repository->comisiones($selectedCalendario, $soloAutorizadas, $sort, $order);
            }
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'comisiones.php';
    }
}
