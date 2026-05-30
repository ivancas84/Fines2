<?php

namespace Fines7\Repositories;

use PDO;

class AlumnoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function alumnoByPersona(string $personaId): ?array
    {
        $sql = "
            SELECT
                alumno.*,
                plan.orientacion AS plan_orientacion,
                plan.resolucion AS plan_resolucion
            FROM alumno
            LEFT JOIN plan ON plan.id = alumno.plan
            WHERE alumno.persona = :persona_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['persona_id' => $personaId]);
        $alumno = $stmt->fetch();

        return $alumno ?: null;
    }

    public function save(string $personaId, ?string $alumnoId, array $data): string
    {
        if ($alumnoId === null || $alumnoId === '') {
            $alumnoId = uniqid();

            $sql = "
                INSERT INTO alumno (
                    id,
                    persona,
                    plan,
                    anio_ingreso,
                    semestre_ingreso,
                    fecha_titulacion,
                    observaciones,
                    confirmado_direccion
                ) VALUES (
                    :id,
                    :persona,
                    :plan,
                    :anio_ingreso,
                    :semestre_ingreso,
                    :fecha_titulacion,
                    :observaciones,
                    :confirmado_direccion
                )
            ";
        } else {
            $sql = "
                UPDATE alumno
                SET
                    plan = :plan,
                    anio_ingreso = :anio_ingreso,
                    semestre_ingreso = :semestre_ingreso,
                    fecha_titulacion = :fecha_titulacion,
                    observaciones = :observaciones,
                    confirmado_direccion = :confirmado_direccion
                WHERE id = :id
                  AND persona = :persona
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $alumnoId,
            'persona' => $personaId,
            'plan' => $data['plan'],
            'anio_ingreso' => $data['anio_ingreso'],
            'semestre_ingreso' => $data['semestre_ingreso'],
            'fecha_titulacion' => $data['fecha_titulacion'],
            'observaciones' => $data['observaciones'],
            'confirmado_direccion' => $data['confirmado_direccion'],
        ]);

        return $alumnoId;
    }

}
