<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\DetallePersonaRepository;
use FinesApp\Repositories\DocenteCalificacionRepository;
use FinesApp\Repositories\PersonaRepository;
use FinesApp\Repositories\TomaRepository;

final class DocenteController extends Controller
{
    public function show(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $personaId = (string) ($vars['id'] ?? '');

        $persona = (new PersonaRepository($this->pdo))->find($personaId);
        if ($persona === null) {
            throw new \RuntimeException('No se encontro la persona solicitada.');
        }

        $tomaRepository = new TomaRepository($this->pdo);

        $this->view->render('docentes/show', [
            'title' => 'Docente',
            'persona' => $persona,
            'tomas' => $tomaRepository->byDocente($personaId),
            'estadosToma' => $tomaRepository->estados(),
            'tiposMovimiento' => $tomaRepository->tiposMovimiento(),
            'estadosContralor' => $tomaRepository->estadosContralor(),
            'calificaciones' => (new DocenteCalificacionRepository($this->pdo))->completadasByDocente($personaId),
            'detalles' => (new DetallePersonaRepository($this->pdo))->byPersona($personaId),
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function updatePersona(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        try {
            (new PersonaRepository($this->pdo))->update($personaId, [
                'nombres' => $this->required($request, 'nombres'),
                'apellidos' => $this->nullableText($request->input('apellidos')),
                'numero_documento' => $this->required($request, 'numero_documento'),
                'cuil1' => $this->nullableInt($request->input('cuil1')),
                'cuil2' => $this->nullableInt($request->input('cuil2')),
                'sexo' => $this->nullableInt($request->input('sexo')),
                'dia_nacimiento' => $this->nullableInt($request->input('dia_nacimiento')),
                'mes_nacimiento' => $this->nullableInt($request->input('mes_nacimiento')),
                'anio_nacimiento' => $this->nullableInt($request->input('anio_nacimiento')),
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
            ]);

            Session::flash('notice', 'Datos de persona guardados.');
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                Session::flash('error', 'No se pudo guardar: DNI, CUIL o Email ABC ya pertenece a otra persona.');
                Response::redirect(url("/personas/{$personaId}/docente"));
            }

            throw $exception;
        }

        Response::redirect(url("/personas/{$personaId}/docente"));
    }

    public function saveTomas(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');

        try {
            (new TomaRepository($this->pdo))->updateForDocente($personaId, $this->tomaRows($request));
            Session::flash('notice', 'Tomas guardadas.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/docente"));
    }

    private function required(Request $request, string $key): string
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException("El campo {$key} es obligatorio.");
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

    private function tomaRows(Request $request): array
    {
        $rows = [];
        $ids = $request->arrayInput('toma_id');
        $fechas = $request->arrayInput('fecha_toma');
        $estados = $request->arrayInput('estado');
        $tipos = $request->arrayInput('tipo_movimiento');
        $estadosContralor = $request->arrayInput('estado_contralor');
        $observaciones = $request->arrayInput('observaciones_toma');

        foreach ($ids as $index => $id) {
            if ((string) $id === '') {
                continue;
            }

            $rows[] = [
                'id' => (string) $id,
                'fecha_toma' => (string) ($fechas[$index] ?? ''),
                'estado' => (string) ($estados[$index] ?? ''),
                'tipo_movimiento' => (string) ($tipos[$index] ?? ''),
                'estado_contralor' => (string) ($estadosContralor[$index] ?? ''),
                'observaciones' => (string) ($observaciones[$index] ?? ''),
            ];
        }

        return $rows;
    }
}
