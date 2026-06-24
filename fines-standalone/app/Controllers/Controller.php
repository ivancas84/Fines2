<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Auth;
use FinesApp\Core\Config;
use FinesApp\Core\Csrf;
use FinesApp\Core\Request;
use FinesApp\Core\View;
use PDO;

abstract class Controller
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly View $view,
        protected readonly Auth $auth,
        protected readonly Csrf $csrf,
        protected readonly Config $config,
    ) {
    }

    protected function requireLogin(): void
    {
        $this->auth->requireLogin();
    }

    protected function requireEdit(): void
    {
        $this->requireLogin();
        if (!$this->auth->canEdit()) {
            throw new \RuntimeException('No tenes permisos para modificar datos.');
        }
    }

    /** @return array{numero_documento: string, cuil1: ?int, cuil2: ?int, sexo: ?int, dia_nacimiento: ?int, mes_nacimiento: ?int, anio_nacimiento: ?int} */
    protected function personaIdentificationData(Request $request): array
    {
        $numeroDocumento = $this->digits($request->input('numero_documento'));
        if (strlen($numeroDocumento) !== 8) {
            throw new \InvalidArgumentException('El DNI debe contener exactamente 8 dígitos.');
        }

        $dia = $this->nullableIntegerInRange($request->input('dia_nacimiento'), 1, 31, 'día de nacimiento');
        $mes = $this->nullableIntegerInRange($request->input('mes_nacimiento'), 1, 12, 'mes de nacimiento');
        $anio = $this->nullableIntegerInRange(
            $request->input('anio_nacimiento'),
            1900,
            (int) date('Y'),
            'año de nacimiento',
        );

        if ($dia !== null && $mes !== null && $anio !== null && !checkdate($mes, $dia, $anio)) {
            throw new \InvalidArgumentException('La fecha de nacimiento indicada no es válida.');
        }

        return [
            'numero_documento' => $numeroDocumento,
            'cuil1' => $this->nullableDigits($request->input('cuil1'), 2, 'CUIL1'),
            'cuil2' => $this->nullableDigits($request->input('cuil2'), 1, 'CUIL2'),
            'sexo' => $this->nullableIntegerInRange($request->input('sexo'), 1, 3, 'sexo'),
            'dia_nacimiento' => $dia,
            'mes_nacimiento' => $mes,
            'anio_nacimiento' => $anio,
        ];
    }

    private function digits(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value) ?? '';
    }

    private function nullableDigits(?string $value, int $length, string $label): ?int
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $digits = $this->digits($value);
        if (strlen($digits) !== $length) {
            throw new \InvalidArgumentException("El campo {$label} debe contener exactamente {$length} dígitos.");
        }

        return (int) $digits;
    }

    private function nullableIntegerInRange(?string $value, int $minimum, int $maximum, string $label): ?int
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException("El campo {$label} debe ser un número entero.");
        }

        $integer = (int) $value;
        if ($integer < $minimum || $integer > $maximum) {
            throw new \InvalidArgumentException("El campo {$label} debe estar entre {$minimum} y {$maximum}.");
        }

        return $integer;
    }
}
