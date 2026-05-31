<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class AlumnoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byPersona(string $personaId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT alumno.*, plan.orientacion AS plan_orientacion, plan.resolucion AS plan_resolucion
            FROM alumno
            LEFT JOIN plan ON plan.id = alumno.plan
            WHERE alumno.persona = :persona_id
        ");
        $stmt->execute(['persona_id' => $personaId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function save(string $personaId, ?string $alumnoId, array $data): string
    {
        if ($alumnoId === null || $alumnoId === '') {
            $alumnoId = uniqid();
            $sql = "
                INSERT INTO alumno (id, persona, plan, anio_ingreso, semestre_ingreso, fecha_titulacion, observaciones, confirmado_direccion)
                VALUES (:id, :persona, :plan, :anio_ingreso, :semestre_ingreso, :fecha_titulacion, :observaciones, :confirmado_direccion)
            ";
        } else {
            $sql = "
                UPDATE alumno
                SET plan = :plan,
                    anio_ingreso = :anio_ingreso,
                    semestre_ingreso = :semestre_ingreso,
                    fecha_titulacion = :fecha_titulacion,
                    observaciones = :observaciones,
                    confirmado_direccion = :confirmado_direccion
                WHERE id = :id AND persona = :persona
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($data, [
            'id' => $alumnoId,
            'persona' => $personaId,
        ]));

        return $alumnoId;
    }
}
