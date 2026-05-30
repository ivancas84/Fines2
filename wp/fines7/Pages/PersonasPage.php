<?php

namespace Fines7\Pages;

use Fines7\Core\Database;
use Fines7\Repositories\PersonaRepository;

class PersonasPage
{
    public static function render(): void
    {
        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('No tienes permisos suficientes para acceder a esta pagina.', 'fines7'));
        }

        $search = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
        $submitted = isset($_GET['submit']);
        $personas = [];
        $error = null;

        if ($submitted && $search !== '') {
            try {
                $repository = new PersonaRepository(Database::fines());
                $personas = $repository->search($search);
            } catch (\Throwable $throwable) {
                $error = $throwable->getMessage();
            }
        }

        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'personas.php';
    }
}
