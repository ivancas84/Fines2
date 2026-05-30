<?php

namespace Fines7\Pages;

use Fines7\Core\Database;
use Fines7\Repositories\AlumnoRepository;

class AlumnoPage
{
    public static function render(): void
    {
        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('No tienes permisos suficientes para acceder a esta pagina.', 'fines7'));
        }

        $personaId = isset($_GET['persona_id']) ? sanitize_text_field(wp_unslash($_GET['persona_id'])) : '';
        $persona = null;
        $alumno = null;
        $comisiones = [];
        $calificaciones = [];
        $detalles = [];
        $error = null;

        if ($personaId === '') {
            $error = 'Falta persona_id para consultar el detalle de alumno.';
        } else {
            try {
                $repository = new AlumnoRepository(Database::fines());
                $persona = $repository->persona($personaId);

                if ($persona === null) {
                    $error = 'No se encontro la persona solicitada.';
                } else {
                    $alumno = $repository->alumnoByPersona($personaId);

                    if ($alumno !== null) {
                        $comisiones = $repository->comisiones((string) $alumno['id']);
                        $calificaciones = $repository->calificaciones((string) $alumno['id']);
                    }

                    $detalles = $repository->detalles($personaId);
                }
            } catch (\Throwable $throwable) {
                $error = $throwable->getMessage();
            }
        }

        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'alumno.php';
    }
}
