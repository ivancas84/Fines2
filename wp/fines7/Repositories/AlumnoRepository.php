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

    public function estadosInscripcion(): array
    {
        $sql = "
            SELECT DISTINCT estado_inscripcion
            FROM alumno
            WHERE estado_inscripcion IS NOT NULL
              AND estado_inscripcion != ''
            ORDER BY estado_inscripcion ASC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function save(string $personaId, ?string $alumnoId, array $data): string
    {
        if ($alumnoId === null || $alumnoId === '') {
            $alumnoId = uniqid();

            $sql = "
                INSERT INTO alumno (
                    id,
                    persona,
                    estado_inscripcion,
                    plan,
                    anio_ingreso,
                    semestre_ingreso,
                    anio_inscripcion,
                    semestre_inscripcion,
                    establecimiento_inscripcion,
                    fecha_titulacion,
                    observaciones,
                    tiene_dni,
                    tiene_constancia,
                    tiene_certificado,
                    previas_completas,
                    tiene_partida,
                    confirmado_direccion
                ) VALUES (
                    :id,
                    :persona,
                    :estado_inscripcion,
                    :plan,
                    :anio_ingreso,
                    :semestre_ingreso,
                    :anio_inscripcion,
                    :semestre_inscripcion,
                    :establecimiento_inscripcion,
                    :fecha_titulacion,
                    :observaciones,
                    :tiene_dni,
                    :tiene_constancia,
                    :tiene_certificado,
                    :previas_completas,
                    :tiene_partida,
                    :confirmado_direccion
                )
            ";
        } else {
            $sql = "
                UPDATE alumno
                SET
                    estado_inscripcion = :estado_inscripcion,
                    plan = :plan,
                    anio_ingreso = :anio_ingreso,
                    semestre_ingreso = :semestre_ingreso,
                    anio_inscripcion = :anio_inscripcion,
                    semestre_inscripcion = :semestre_inscripcion,
                    establecimiento_inscripcion = :establecimiento_inscripcion,
                    fecha_titulacion = :fecha_titulacion,
                    observaciones = :observaciones,
                    tiene_dni = :tiene_dni,
                    tiene_constancia = :tiene_constancia,
                    tiene_certificado = :tiene_certificado,
                    previas_completas = :previas_completas,
                    tiene_partida = :tiene_partida,
                    confirmado_direccion = :confirmado_direccion
                WHERE id = :id
                  AND persona = :persona
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $alumnoId,
            'persona' => $personaId,
            'estado_inscripcion' => $data['estado_inscripcion'],
            'plan' => $data['plan'],
            'anio_ingreso' => $data['anio_ingreso'],
            'semestre_ingreso' => $data['semestre_ingreso'],
            'anio_inscripcion' => $data['anio_inscripcion'],
            'semestre_inscripcion' => $data['semestre_inscripcion'],
            'establecimiento_inscripcion' => $data['establecimiento_inscripcion'],
            'fecha_titulacion' => $data['fecha_titulacion'],
            'observaciones' => $data['observaciones'],
            'tiene_dni' => $data['tiene_dni'],
            'tiene_constancia' => $data['tiene_constancia'],
            'tiene_certificado' => $data['tiene_certificado'],
            'previas_completas' => $data['previas_completas'],
            'tiene_partida' => $data['tiene_partida'],
            'confirmado_direccion' => $data['confirmado_direccion'],
        ]);

        return $alumnoId;
    }

}
