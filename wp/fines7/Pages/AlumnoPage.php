<?php

namespace Fines7\Pages;

use Fines7\Core\Database;
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
        $estadosInscripcion = [];
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
                    $estadosInscripcion = $alumnoRepository->estadosInscripcion();
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

    public static function maybeHandlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';
        if ($page !== \Fines7\Core\Plugin::ALUMNO_SLUG) {
            return;
        }

        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('No tienes permisos suficientes para realizar esta accion.', 'fines7'));
        }

        $personaId = isset($_GET['persona_id']) ? sanitize_text_field(wp_unslash($_GET['persona_id'])) : '';
        if ($personaId === '') {
            return;
        }

        $pdo = Database::fines();
        self::handlePost(new PersonaRepository($pdo), new AlumnoRepository($pdo), $personaId);
    }

    private static function handlePost(PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, string $personaId): void
    {
        $action = isset($_POST['fines7_action']) ? sanitize_key(wp_unslash($_POST['fines7_action'])) : '';

        if ($action === 'update_persona') {
            check_admin_referer('fines7_update_persona_' . $personaId);
            $personaRepository->update($personaId, self::personaDataFromPost());
            self::redirectWithNotice($personaId, 'persona_guardada');
        }

        if ($action === 'save_alumno') {
            check_admin_referer('fines7_save_alumno_' . $personaId);
            $alumnoId = isset($_POST['alumno_id']) ? sanitize_text_field(wp_unslash($_POST['alumno_id'])) : '';
            $alumnoRepository->save($personaId, $alumnoId, self::alumnoDataFromPost());
            self::redirectWithNotice($personaId, 'alumno_guardado');
        }
    }

    private static function personaDataFromPost(): array
    {
        $data = [
            'nombres' => self::postText('nombres'),
            'apellidos' => self::postText('apellidos'),
            'numero_documento' => self::postText('numero_documento'),
            'cuil' => self::postText('cuil'),
            'cuil1' => self::postInt('cuil1'),
            'cuil2' => self::postInt('cuil2'),
            'sexo' => self::postInt('sexo'),
            'dia_nacimiento' => self::postInt('dia_nacimiento'),
            'mes_nacimiento' => self::postInt('mes_nacimiento'),
            'anio_nacimiento' => self::postInt('anio_nacimiento'),
            'telefono' => self::postText('telefono'),
            'codigo_area' => self::postText('codigo_area'),
            'email' => self::postEmail('email'),
            'email_abc' => self::postEmail('email_abc'),
            'lugar_nacimiento' => self::postText('lugar_nacimiento'),
            'nacionalidad' => self::postText('nacionalidad'),
            'descripcion_domicilio' => self::postText('descripcion_domicilio'),
            'departamento' => self::postText('departamento'),
            'localidad' => self::postText('localidad'),
            'partido' => self::postText('partido'),
        ];

        if ($data['nombres'] === null || $data['numero_documento'] === null) {
            throw new \InvalidArgumentException('Nombres y DNI son obligatorios.');
        }

        return $data;
    }

    private static function alumnoDataFromPost(): array
    {
        return [
            'estado_inscripcion' => self::postText('estado_inscripcion'),
            'plan' => self::postText('plan'),
            'anio_ingreso' => self::postText('anio_ingreso'),
            'semestre_ingreso' => self::postInt('semestre_ingreso'),
            'anio_inscripcion' => self::postInt('anio_inscripcion'),
            'semestre_inscripcion' => self::postInt('semestre_inscripcion'),
            'establecimiento_inscripcion' => self::postText('establecimiento_inscripcion'),
            'fecha_titulacion' => self::postDate('fecha_titulacion'),
            'observaciones' => self::postTextarea('observaciones'),
            'tiene_dni' => self::postBool('tiene_dni'),
            'tiene_constancia' => self::postBool('tiene_constancia'),
            'tiene_certificado' => self::postBool('tiene_certificado'),
            'previas_completas' => self::postBool('previas_completas'),
            'tiene_partida' => self::postBool('tiene_partida'),
            'confirmado_direccion' => self::postBool('confirmado_direccion'),
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

    private static function postText(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    private static function postTextarea(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_textarea_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    private static function postEmail(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_email(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : $value;
    }

    private static function postInt(string $key): ?int
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        return $value === '' ? null : (int) $value;
    }

    private static function postDate(string $key): ?string
    {
        $value = isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
        if ($value === '') {
            return null;
        }

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 ? $value : null;
    }

    private static function postBool(string $key): int
    {
        return isset($_POST[$key]) ? 1 : 0;
    }
}
