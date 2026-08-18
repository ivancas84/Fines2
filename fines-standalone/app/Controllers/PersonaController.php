<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\AlumnoRepository;
use FinesApp\Repositories\PersonaRepository;
use FinesApp\Repositories\PlanRepository;

final class PersonaController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $search = (string) $request->query('q', '');
        $personas = [];
        $submitted = $search !== '';

        if ($submitted) {
            $personas = (new PersonaRepository($this->pdo))->search($search);
        }

        $data = [
            'title' => 'Personas',
            'search' => $search,
            'submitted' => $submitted,
            'personas' => $personas,
            'notice' => $request->isHtmx() ? null : flash('notice'),
            'error' => $request->isHtmx() ? null : flash('error'),
        ];

        if ($request->isHtmx()) {
            $this->view->renderPartial('personas/_results', $data);
            return;
        }

        $this->view->render('personas/index', $data);
    }

    public function createForm(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $this->renderForm($this->emptyPersonaValues(), 'persona', $this->emptyAlumnoValues());
    }

    public function create(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $funcion = $this->funcionFromRequest($request);
        $personaValues = $this->personaValuesFromRequest($request);
        $alumnoValues = $this->alumnoValuesFromRequest($request);

        try {
            $data = $this->personaInsertData($request);
            $repository = new PersonaRepository($this->pdo);

            $existingDocumento = $repository->findByDocumento($data['numero_documento']);
            if ($existingDocumento !== null) {
                $this->renderForm(
                    $personaValues,
                    $funcion,
                    $alumnoValues,
                    'Ya existe una persona con ese DNI.',
                    $existingDocumento,
                );
                return;
            }

            if ($data['email_abc'] !== null) {
                $existingEmail = $repository->findByEmailAbc($data['email_abc']);
                if ($existingEmail !== null) {
                    $this->renderForm(
                        $personaValues,
                        $funcion,
                        $alumnoValues,
                        'Ya existe una persona con ese email ABC.',
                        $existingEmail,
                    );
                    return;
                }
            }

            $this->pdo->beginTransaction();
            try {
                $personaId = $repository->create($data);
                if ($funcion === 'alumno') {
                    (new AlumnoRepository($this->pdo))->save($personaId, null, $this->alumnoInsertData($request));
                }
                $this->pdo->commit();
            } catch (\Throwable $throwable) {
                $this->pdo->rollBack();
                throw $throwable;
            }

            if ($funcion === 'alumno') {
                Session::flash('notice', 'Persona y ficha de alumno creadas.');
                Response::redirect(url("/personas/{$personaId}/alumno"));
            }

            if ($funcion === 'docente') {
                Session::flash('notice', 'Persona creada. Las tomas se cargan después desde una comisión o esta ficha.');
                Response::redirect(url("/personas/{$personaId}/docente"));
            }

            Session::flash(
                'notice',
                'Persona creada. Ya podés asignarla como designación en una sede o abrir su ficha de alumno o docente.',
            );
            Response::redirect(url('/personas?q=' . rawurlencode($data['numero_documento'])));
        } catch (\InvalidArgumentException $exception) {
            $this->renderForm($personaValues, $funcion, $alumnoValues, $exception->getMessage());
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $this->renderForm(
                    $personaValues,
                    $funcion,
                    $alumnoValues,
                    'No se pudo guardar: DNI, CUIL o Email ABC ya pertenece a otra persona.',
                );
                return;
            }

            throw $exception;
        }
    }

    /**
     * @param array<string, mixed> $persona
     * @param array<string, mixed> $alumno
     * @param array<string, mixed>|null $existing
     */
    private function renderForm(
        array $persona,
        string $funcion,
        array $alumno,
        ?string $error = null,
        ?array $existing = null,
    ): void {
        $this->view->render('personas/form', [
            'title' => 'Nueva persona',
            'persona' => $persona,
            'funcion' => $funcion,
            'alumno' => $alumno,
            'planes' => (new PlanRepository($this->pdo))->all(),
            'existing' => $existing,
            'error' => $error,
        ]);
    }

    /**
     * @return array{
     *   nombres: string,
     *   apellidos: ?string,
     *   numero_documento: string,
     *   cuil1: ?int,
     *   cuil2: ?int,
     *   sexo: ?int,
     *   dia_nacimiento: ?int,
     *   mes_nacimiento: ?int,
     *   anio_nacimiento: ?int,
     *   telefono: ?string,
     *   codigo_area: ?string,
     *   email: ?string,
     *   email_abc: ?string,
     *   lugar_nacimiento: ?string,
     *   nacionalidad: ?string,
     *   descripcion_domicilio: ?string,
     *   departamento: ?string,
     *   localidad: ?string,
     *   partido: ?string
     * }
     */
    private function personaInsertData(Request $request): array
    {
        $nombres = $this->required($request, 'nombres');
        $apellidos = $this->required($request, 'apellidos');

        return [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            ...$this->personaIdentificationData($request),
            'telefono' => $this->nullableText($request->input('telefono')),
            'codigo_area' => $this->nullableText($request->input('codigo_area')),
            'email' => $this->nullableText($request->input('email')),
            'email_abc' => $this->nullableText($request->input('email_abc')),
            'lugar_nacimiento' => $this->nullableText($request->input('lugar_nacimiento')),
            'nacionalidad' => $this->nullableText($request->input('nacionalidad')),
            'descripcion_domicilio' => $this->nullableText($request->input('descripcion_domicilio')),
            'departamento' => $this->nullableText($request->input('departamento')),
            'localidad' => $this->nullableText($request->input('localidad')),
            'partido' => $this->nullableText($request->input('partido')),
        ];
    }

    /**
     * @return array{
     *   plan: ?string,
     *   anio_ingreso: ?string,
     *   semestre_ingreso: ?int,
     *   fecha_titulacion: ?string,
     *   observaciones: ?string,
     *   confirmado_direccion: int
     * }
     */
    private function alumnoInsertData(Request $request): array
    {
        return [
            'plan' => $this->nullableText($request->input('plan')),
            'anio_ingreso' => $this->nullableText($request->input('anio_ingreso')),
            'semestre_ingreso' => $this->nullableInt($request->input('semestre_ingreso')),
            'fecha_titulacion' => $this->nullableText($request->input('fecha_titulacion')),
            'observaciones' => $this->nullableText($request->input('observaciones')),
            'confirmado_direccion' => $request->checkbox('confirmado_direccion'),
        ];
    }

    private function funcionFromRequest(Request $request): string
    {
        $funcion = (string) $request->input('funcion', 'persona');

        return in_array($funcion, ['persona', 'alumno', 'docente'], true) ? $funcion : 'persona';
    }

    /** @return array<string, string> */
    private function emptyPersonaValues(): array
    {
        return [
            'nombres' => '',
            'apellidos' => '',
            'sexo' => '',
            'cuil1' => '',
            'numero_documento' => '',
            'cuil2' => '',
            'dia_nacimiento' => '',
            'mes_nacimiento' => '',
            'anio_nacimiento' => '',
            'codigo_area' => '',
            'telefono' => '',
            'email' => '',
            'email_abc' => '',
            'lugar_nacimiento' => '',
            'nacionalidad' => '',
            'descripcion_domicilio' => '',
            'departamento' => '',
            'localidad' => '',
            'partido' => '',
        ];
    }

    /** @return array<string, string|int> */
    private function emptyAlumnoValues(): array
    {
        return [
            'plan' => '',
            'anio_ingreso' => '',
            'semestre_ingreso' => '',
            'fecha_titulacion' => '',
            'observaciones' => '',
            'confirmado_direccion' => 0,
        ];
    }

    /** @return array<string, string> */
    private function personaValuesFromRequest(Request $request): array
    {
        $values = $this->emptyPersonaValues();
        foreach (array_keys($values) as $key) {
            $values[$key] = (string) $request->input($key, '');
        }

        return $values;
    }

    /** @return array<string, string|int> */
    private function alumnoValuesFromRequest(Request $request): array
    {
        return [
            'plan' => (string) $request->input('plan', ''),
            'anio_ingreso' => (string) $request->input('anio_ingreso', ''),
            'semestre_ingreso' => (string) $request->input('semestre_ingreso', ''),
            'fecha_titulacion' => (string) $request->input('fecha_titulacion', ''),
            'observaciones' => (string) $request->input('observaciones', ''),
            'confirmado_direccion' => $request->checkbox('confirmado_direccion'),
        ];
    }

    private function required(Request $request, string $key): string
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            $labels = [
                'nombres' => 'nombres',
                'apellidos' => 'apellidos',
            ];
            $label = $labels[$key] ?? $key;
            throw new \InvalidArgumentException("El campo {$label} es obligatorio.");
        }

        return $value;
    }

    private function nullableInt(?string $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }

    private function nullableText(?string $value): ?string
    {
        return $value === null || $value === '' ? null : $value;
    }
}
