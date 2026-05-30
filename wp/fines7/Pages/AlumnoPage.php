<?php

namespace Fines7\Pages;

use Fines7\Core\AdminRequest;
use Fines7\Core\Database;
use Fines7\Core\FormData;
use Fines7\Repositories\AlumnoComisionRepository;
use Fines7\Repositories\AlumnoRepository;
use Fines7\Repositories\CalificacionRepository;
use Fines7\Repositories\DetallePersonaRepository;
use Fines7\Repositories\PersonaRepository;
use Fines7\Repositories\PlanRepository;

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
        $planes = [];
        $comisiones = [];
        $calificaciones = [];
        $detalles = [];
        $error = null;
        $notice = isset($_GET['fines7_notice']) ? sanitize_text_field(wp_unslash($_GET['fines7_notice'])) : '';

        if ($personaId === '') {
            $error = 'Falta persona_id para consultar el detalle de alumno.';
        } else {
            try {
                $pdo = Database::fines();
                $personaRepository = new PersonaRepository($pdo);
                $alumnoRepository = new AlumnoRepository($pdo);
                $planRepository = new PlanRepository($pdo);
                $alumnoComisionRepository = new AlumnoComisionRepository($pdo);
                $calificacionRepository = new CalificacionRepository($pdo);
                $detallePersonaRepository = new DetallePersonaRepository($pdo);

                $persona = $personaRepository->find($personaId);

                if ($persona === null) {
                    $error = 'No se encontro la persona solicitada.';
                } else {
                    $planes = $planRepository->all();
                    $alumno = $alumnoRepository->alumnoByPersona($personaId);

                    if ($alumno !== null) {
                        $comisiones = $alumnoComisionRepository->byAlumno((string) $alumno['id']);
                        $calificaciones = $calificacionRepository->byAlumno((string) $alumno['id']);
                    }

                    $detalles = $detallePersonaRepository->byPersona($personaId);
                }
            } catch (\Throwable $throwable) {
                $error = $throwable->getMessage();
            }
        }

        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'alumno.php';
    }

    public static function updatePersona(): void
    {
        AdminRequest::requireCapability('edit_posts');
        $personaId = FormData::requiredText('persona_id', 'Falta persona_id para procesar el formulario.');
        check_admin_referer('fines7_update_persona_' . $personaId);

        $personaRepository = new PersonaRepository(Database::fines());
        $personaRepository->update($personaId, self::personaDataFromPost());
        self::redirectWithNotice($personaId, 'persona_guardada');
    }

    public static function saveAlumno(): void
    {
        AdminRequest::requireCapability('edit_posts');
        $personaId = FormData::requiredText('persona_id', 'Falta persona_id para procesar el formulario.');
        check_admin_referer('fines7_save_alumno_' . $personaId);

        $alumnoRepository = new AlumnoRepository(Database::fines());
        $alumnoId = FormData::text('alumno_id');
        $alumnoRepository->save($personaId, $alumnoId, self::alumnoDataFromPost());
        self::redirectWithNotice($personaId, 'alumno_guardado');
    }

    private static function personaDataFromPost(): array
    {
        $data = [
            'nombres' => FormData::text('nombres'),
            'apellidos' => FormData::text('apellidos'),
            'numero_documento' => FormData::text('numero_documento'),
            'cuil1' => FormData::int('cuil1'),
            'cuil2' => FormData::int('cuil2'),
            'sexo' => FormData::int('sexo'),
            'dia_nacimiento' => FormData::int('dia_nacimiento'),
            'mes_nacimiento' => FormData::int('mes_nacimiento'),
            'anio_nacimiento' => FormData::int('anio_nacimiento'),
            'telefono' => FormData::text('telefono'),
            'codigo_area' => FormData::text('codigo_area'),
            'email' => FormData::email('email'),
            'email_abc' => FormData::email('email_abc'),
            'lugar_nacimiento' => FormData::text('lugar_nacimiento'),
            'nacionalidad' => FormData::text('nacionalidad'),
            'descripcion_domicilio' => FormData::text('descripcion_domicilio'),
            'departamento' => FormData::text('departamento'),
            'localidad' => FormData::text('localidad'),
            'partido' => FormData::text('partido'),
        ];

        if ($data['nombres'] === null || $data['numero_documento'] === null) {
            throw new \InvalidArgumentException('Nombres y DNI son obligatorios.');
        }

        return $data;
    }

    private static function alumnoDataFromPost(): array
    {
        return [
            'plan' => FormData::text('plan'),
            'anio_ingreso' => FormData::text('anio_ingreso'),
            'semestre_ingreso' => FormData::int('semestre_ingreso'),
            'fecha_titulacion' => FormData::date('fecha_titulacion'),
            'observaciones' => FormData::textarea('observaciones'),
            'confirmado_direccion' => FormData::bool('confirmado_direccion'),
        ];
    }

    private static function redirectWithNotice(string $personaId, string $notice): void
    {
        wp_safe_redirect(add_query_arg([
            'page' => \Fines7\Core\Plugin::ALUMNO_SLUG,
            'persona_id' => $personaId,
            'fines7_notice' => $notice,
        ], admin_url('admin.php')));
        exit;
    }

}
