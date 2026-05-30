<?php

namespace Fines7\Repositories;

use PDO;

class AlumnoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function persona(string $personaId): ?array
    {
        $sql = "
            SELECT
                id,
                nombres,
                apellidos,
                numero_documento,
                cuil,
                cuil1,
                cuil2,
                sexo,
                dia_nacimiento,
                mes_nacimiento,
                anio_nacimiento,
                telefono,
                codigo_area,
                email,
                email_abc,
                lugar_nacimiento,
                nacionalidad,
                descripcion_domicilio,
                departamento,
                localidad,
                partido
            FROM persona
            WHERE id = :persona_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['persona_id' => $personaId]);
        $persona = $stmt->fetch();

        return $persona ?: null;
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

    public function planes(): array
    {
        $sql = "
            SELECT id, orientacion, resolucion
            FROM plan
            ORDER BY orientacion ASC, resolucion ASC
        ";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function updatePersona(string $personaId, array $data): void
    {
        $sql = "
            UPDATE persona
            SET
                nombres = :nombres,
                apellidos = :apellidos,
                numero_documento = :numero_documento,
                cuil = :cuil,
                cuil1 = :cuil1,
                cuil2 = :cuil2,
                sexo = :sexo,
                dia_nacimiento = :dia_nacimiento,
                mes_nacimiento = :mes_nacimiento,
                anio_nacimiento = :anio_nacimiento,
                telefono = :telefono,
                codigo_area = :codigo_area,
                email = :email,
                email_abc = :email_abc,
                lugar_nacimiento = :lugar_nacimiento,
                nacionalidad = :nacionalidad,
                descripcion_domicilio = :descripcion_domicilio,
                departamento = :departamento,
                localidad = :localidad,
                partido = :partido
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $personaId,
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'numero_documento' => $data['numero_documento'],
            'cuil' => $data['cuil'],
            'cuil1' => $data['cuil1'],
            'cuil2' => $data['cuil2'],
            'sexo' => $data['sexo'],
            'dia_nacimiento' => $data['dia_nacimiento'],
            'mes_nacimiento' => $data['mes_nacimiento'],
            'anio_nacimiento' => $data['anio_nacimiento'],
            'telefono' => $data['telefono'],
            'codigo_area' => $data['codigo_area'],
            'email' => $data['email'],
            'email_abc' => $data['email_abc'],
            'lugar_nacimiento' => $data['lugar_nacimiento'],
            'nacionalidad' => $data['nacionalidad'],
            'descripcion_domicilio' => $data['descripcion_domicilio'],
            'departamento' => $data['departamento'],
            'localidad' => $data['localidad'],
            'partido' => $data['partido'],
        ]);
    }

    public function saveAlumno(string $personaId, ?string $alumnoId, array $data): string
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

    public function comisiones(string $alumnoId): array
    {
        $sql = "
            SELECT
                alumno_comision.id,
                alumno_comision.estado,
                alumno_comision.activo,
                alumno_comision.observaciones,
                comision.pfid,
                COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(calendario.anio, ''),
                    NULLIF(calendario.semestre, '')
                )) AS calendario_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(planificacion.anio, ''),
                    NULLIF(planificacion.semestre, '')
                )) AS tramo_label,
                plan.orientacion AS plan_orientacion,
                plan.resolucion AS plan_resolucion
            FROM alumno_comision
            LEFT JOIN comision ON comision.id = alumno_comision.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE alumno_comision.alumno = :alumno_id
            ORDER BY alumno_comision.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['alumno_id' => $alumnoId]);

        return $stmt->fetchAll();
    }

    public function calificaciones(string $alumnoId): array
    {
        $sql = "
            SELECT
                calificacion.id,
                asignatura.nombre AS asignatura_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(planificacion.anio, ''),
                    NULLIF(planificacion.semestre, '')
                )) AS tramo_label,
                plan.orientacion AS plan_orientacion,
                plan.resolucion AS plan_resolucion,
                calificacion.nota_final,
                calificacion.crec,
                calificacion.observaciones,
                calificacion.curso,
                comision.pfid,
                TRIM(CONCAT_WS('-',
                    NULLIF(calendario.anio, ''),
                    NULLIF(calendario.semestre, '')
                )) AS calendario_label,
                COALESCE(toma_activa.docente_label, '') AS docente_label
            FROM calificacion
            INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            INNER JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN curso ON curso.id = calificacion.curso
            LEFT JOIN comision ON comision.id = curso.comision
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN (
                SELECT
                    toma.curso,
                    GROUP_CONCAT(
                        NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), '')
                        ORDER BY persona.apellidos, persona.nombres
                        SEPARATOR ', '
                    ) AS docente_label
                FROM toma
                LEFT JOIN persona ON persona.id = toma.docente
                WHERE toma.estado = 'Aprobada'
                  AND toma.estado_contralor = 'Pasar'
                GROUP BY toma.curso
            ) toma_activa ON toma_activa.curso = curso.id
            WHERE calificacion.alumno = :alumno_id
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     asignatura.nombre ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['alumno_id' => $alumnoId]);

        return $stmt->fetchAll();
    }

    public function detalles(string $personaId): array
    {
        $sql = "
            SELECT
                detalle_persona.id,
                detalle_persona.descripcion,
                detalle_persona.fecha,
                detalle_persona.tipo,
                detalle_persona.asunto,
                `file`.content AS file_content
            FROM detalle_persona
            LEFT JOIN `file` ON `file`.id = detalle_persona.archivo
            WHERE detalle_persona.persona = :persona_id
            ORDER BY detalle_persona.fecha DESC, detalle_persona.creado DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['persona_id' => $personaId]);

        return $stmt->fetchAll();
    }
}
