<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class ComisionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function calendarios(): array
    {
        return $this->pdo
            ->query('SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC')
            ->fetchAll();
    }

    public function byId(string $comisionId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.pfid,
                   comision.turno,
                   comision.autorizada,
                   comision.apertura,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   calendario.descripcion AS calendario_descripcion,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS planificacion_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE comision.id = :comision_id
            LIMIT 1
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function alumnos(string $comisionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT alumno_comision.id AS alumno_comision_id,
                   alumno_comision.activo,
                   alumno_comision.estado,
                   alumno.id AS alumno_id,
                   alumno.persona AS persona_id,
                   alumno.plan AS alumno_plan_id,
                   alumno.anio_ingreso,
                   alumno.semestre_ingreso,
                   persona.apellidos,
                   persona.nombres,
                   persona.cuil1,
                   persona.numero_documento,
                   persona.cuil2,
                   persona.codigo_area,
                   persona.telefono,
                   persona.email,
                   persona.dia_nacimiento,
                   persona.mes_nacimiento,
                   persona.anio_nacimiento,
                   persona.sexo
            FROM alumno_comision
            INNER JOIN alumno ON alumno.id = alumno_comision.alumno
            INNER JOIN persona ON persona.id = alumno.persona
            WHERE alumno_comision.comision = :comision_id
            ORDER BY alumno_comision.activo DESC,
                     persona.apellidos ASC,
                     persona.nombres ASC
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $alumnos = $stmt->fetchAll();

        if ($alumnos === []) {
            return [];
        }

        $aprobadasStmt = $this->pdo->prepare("
            SELECT calificacion.alumno AS alumno_id,
                   planificacion.plan AS plan_id,
                   planificacion.anio,
                   planificacion.semestre,
                   COUNT(DISTINCT calificacion.id) AS cantidad
            FROM alumno_comision
            INNER JOIN calificacion ON calificacion.alumno = alumno_comision.alumno
            INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            WHERE alumno_comision.comision = :comision_id
              AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
            GROUP BY calificacion.alumno,
                     planificacion.plan,
                     planificacion.anio,
                     planificacion.semestre
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC
        ");
        $aprobadasStmt->execute(['comision_id' => $comisionId]);

        $aprobadasPorAlumno = [];
        foreach ($aprobadasStmt->fetchAll() as $aprobada) {
            $aprobadasPorAlumno[(string) $aprobada['alumno_id']][] = $aprobada;
        }

        foreach ($alumnos as &$alumno) {
            $tramoIngreso = $this->tramoIngresoShort($alumno);
            $planId = (string) ($alumno['alumno_plan_id'] ?? '');
            $porTramo = [];
            $otrosPlanes = 0;

            foreach ($aprobadasPorAlumno[(string) $alumno['alumno_id']] ?? [] as $aprobada) {
                $tramoCalificacion = (string) $aprobada['anio'] . (string) $aprobada['semestre'];
                if ($tramoCalificacion < $tramoIngreso) {
                    continue;
                }

                $cantidad = (int) $aprobada['cantidad'];
                if ($planId !== '' && (string) $aprobada['plan_id'] === $planId) {
                    $porTramo[] = [
                        'label' => $aprobada['anio'] . '° ' . $aprobada['semestre'] . 'C',
                        'cantidad' => $cantidad,
                    ];
                } elseif ($planId !== '') {
                    $otrosPlanes += $cantidad;
                }
            }

            $alumno['aprobadas_por_tramo'] = $porTramo;
            $alumno['aprobadas_otros_planes'] = $otrosPlanes;
        }
        unset($alumno);

        return $alumnos;
    }

    public function alumnoComision(string $comisionId, string $alumnoComisionId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT alumno_comision.id AS alumno_comision_id,
                   alumno_comision.comision AS comision_id,
                   alumno.id AS alumno_id,
                   persona.id AS persona_id,
                   persona.nombres,
                   persona.apellidos,
                   persona.numero_documento,
                   persona.cuil1,
                   persona.cuil2,
                   persona.sexo,
                   persona.dia_nacimiento,
                   persona.mes_nacimiento,
                   persona.anio_nacimiento,
                   persona.telefono,
                   persona.codigo_area,
                   persona.email,
                   persona.nacionalidad,
                   persona.descripcion_domicilio,
                   persona.departamento,
                   persona.localidad,
                   persona.partido,
                   comision.pfid
            FROM alumno_comision
            INNER JOIN alumno ON alumno.id = alumno_comision.alumno
            INNER JOIN persona ON persona.id = alumno.persona
            INNER JOIN comision ON comision.id = alumno_comision.comision
            WHERE alumno_comision.id = :alumno_comision_id
              AND alumno_comision.comision = :comision_id
            LIMIT 1
        ");
        $stmt->execute([
            'alumno_comision_id' => $alumnoComisionId,
            'comision_id' => $comisionId,
        ]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function importProgramaFinesStudent(string $comisionId, array $remote): array
    {
        $dni = preg_replace('/\D+/', '', (string) ($remote['dni'] ?? $remote['numero_documento'] ?? '')) ?? '';
        if ($dni === '') {
            throw new \RuntimeException('ProgramaFines no devolvió un DNI válido.');
        }

        $this->pdo->beginTransaction();
        try {
            $comisionStmt = $this->pdo->prepare("
                SELECT comision.id, planificacion.plan
                FROM comision
                LEFT JOIN planificacion ON planificacion.id = comision.planificacion
                WHERE comision.id = :id
                LIMIT 1
            ");
            $comisionStmt->execute(['id' => $comisionId]);
            $comision = $comisionStmt->fetch();
            if (!$comision) {
                throw new \RuntimeException('La comisión local no existe.');
            }

            $personaStmt = $this->pdo->prepare('SELECT id FROM persona WHERE numero_documento = :dni LIMIT 1');
            $personaStmt->execute(['dni' => $dni]);
            $personaId = $personaStmt->fetchColumn();
            $personaCreated = false;

            $personaData = $this->remotePersonaData($remote);
            if ($personaId === false) {
                $personaId = uniqid();
                $insertPersona = $this->pdo->prepare("
                    INSERT INTO persona (
                        id, nombres, apellidos, numero_documento, cuil1, cuil2, sexo,
                        dia_nacimiento, mes_nacimiento, anio_nacimiento, telefono, codigo_area,
                        email, nacionalidad, descripcion_domicilio, departamento, localidad, partido
                    ) VALUES (
                        :id, :nombres, :apellidos, :numero_documento, :cuil1, :cuil2, :sexo,
                        :dia_nacimiento, :mes_nacimiento, :anio_nacimiento, :telefono, :codigo_area,
                        :email, :nacionalidad, :descripcion_domicilio, :departamento, :localidad, :partido
                    )
                ");
                $insertPersona->execute(array_merge($personaData, ['id' => $personaId]));
                $personaCreated = true;
            } else {
                $personaId = (string) $personaId;
                $updatePersona = $this->pdo->prepare("
                    UPDATE persona SET
                        nombres = COALESCE(NULLIF(nombres, ''), :nombres),
                        apellidos = COALESCE(NULLIF(apellidos, ''), :apellidos),
                        numero_documento = :numero_documento,
                        cuil1 = COALESCE(cuil1, :cuil1),
                        cuil2 = COALESCE(cuil2, :cuil2),
                        sexo = COALESCE(sexo, :sexo),
                        dia_nacimiento = COALESCE(dia_nacimiento, :dia_nacimiento),
                        mes_nacimiento = COALESCE(mes_nacimiento, :mes_nacimiento),
                        anio_nacimiento = COALESCE(anio_nacimiento, :anio_nacimiento),
                        telefono = COALESCE(NULLIF(telefono, ''), :telefono),
                        codigo_area = COALESCE(NULLIF(codigo_area, ''), :codigo_area),
                        email = COALESCE(NULLIF(email, ''), :email),
                        nacionalidad = COALESCE(NULLIF(nacionalidad, ''), :nacionalidad),
                        descripcion_domicilio = COALESCE(NULLIF(descripcion_domicilio, ''), :descripcion_domicilio),
                        departamento = COALESCE(NULLIF(departamento, ''), :departamento),
                        localidad = COALESCE(NULLIF(localidad, ''), :localidad),
                        partido = COALESCE(NULLIF(partido, ''), :partido)
                    WHERE id = :id
                ");
                $updatePersona->execute(array_merge($personaData, ['id' => $personaId]));
            }

            $alumnoStmt = $this->pdo->prepare('SELECT id FROM alumno WHERE persona = :persona LIMIT 1');
            $alumnoStmt->execute(['persona' => $personaId]);
            $alumnoId = $alumnoStmt->fetchColumn();
            $alumnoCreated = false;
            if ($alumnoId === false) {
                $alumnoId = uniqid();
                $insertAlumno = $this->pdo->prepare("
                    INSERT INTO alumno (id, persona, plan)
                    VALUES (:id, :persona, :plan)
                ");
                $insertAlumno->execute([
                    'id' => $alumnoId,
                    'persona' => $personaId,
                    'plan' => $comision['plan'] ?: null,
                ]);
                $alumnoCreated = true;
            } else {
                $alumnoId = (string) $alumnoId;
            }

            $relationStmt = $this->pdo->prepare("
                SELECT id FROM alumno_comision
                WHERE alumno = :alumno AND comision = :comision
                LIMIT 1
            ");
            $relationStmt->execute(['alumno' => $alumnoId, 'comision' => $comisionId]);
            $relationId = $relationStmt->fetchColumn();
            $relationCreated = false;
            if ($relationId === false) {
                $relationId = uniqid();
                $insertRelation = $this->pdo->prepare("
                    INSERT INTO alumno_comision (id, alumno, comision, estado, activo)
                    VALUES (:id, :alumno, :comision, 'Activo', 1)
                ");
                $insertRelation->execute([
                    'id' => $relationId,
                    'alumno' => $alumnoId,
                    'comision' => $comisionId,
                ]);
                $relationCreated = true;
            }

            $this->pdo->commit();
            return [
                'persona_created' => $personaCreated,
                'alumno_created' => $alumnoCreated,
                'relation_created' => $relationCreated,
            ];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    public function comisiones(string $calendarioId, bool $soloAutorizadas, string $sort = 'pfid', string $order = 'asc'): array
    {
        $autorizadaSql = $soloAutorizadas ? 'AND comision.autorizada = 1' : '';
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.sede AS sede_id,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS(' ', NULLIF(domicilio.calle, ''), NULLIF(domicilio.entre, ''), NULLIF(domicilio.numero, ''), NULLIF(domicilio.barrio, ''), NULLIF(domicilio.localidad, ''))) AS domicilio_label,
                   comision.pfid,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS planificacion_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   comision.autorizada,
                   comision.apertura,
                   comision.turno,
                   COALESCE(alumnos.cantidad_alumnos, 0) AS cantidad_alumnos,
                   COALESCE(alumnos.cantidad_alumnos_activos, 0) AS cantidad_alumnos_activos,
                   comision_siguiente.pfid AS comision_siguiente_pfid,
                   planificacion_siguiente.anio AS comision_siguiente_anio,
                   planificacion_siguiente.semestre AS comision_siguiente_semestre,
                   COALESCE(referentes.referentes_label, 'Sin Referentes') AS referentes_label
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN comision comision_siguiente ON comision_siguiente.id = comision.comision_siguiente
            LEFT JOIN planificacion planificacion_siguiente ON planificacion_siguiente.id = comision_siguiente.planificacion
            LEFT JOIN (
                SELECT alumno_comision.comision,
                       COUNT(alumno_comision.id) AS cantidad_alumnos,
                       SUM(alumno_comision.activo = 1) AS cantidad_alumnos_activos
                FROM alumno_comision
                INNER JOIN comision c2 ON c2.id = alumno_comision.comision
                WHERE c2.calendario = :calendario_alumnos
                GROUP BY alumno_comision.comision
            ) alumnos ON alumnos.comision = comision.id
            LEFT JOIN (
                SELECT designacion.sede,
                       GROUP_CONCAT(CONCAT_WS(' ', NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), ''), NULLIF(TRIM(persona.telefono), '')) ORDER BY persona.apellidos, persona.nombres SEPARATOR ', ') AS referentes_label
                FROM designacion
                INNER JOIN persona ON persona.id = designacion.persona
                WHERE designacion.cargo = '1' AND designacion.hasta IS NULL
                GROUP BY designacion.sede
            ) referentes ON referentes.sede = comision.sede
            WHERE comision.calendario = :calendario_comision
            {$autorizadaSql}
            ORDER BY {$orderBy}
        ");
        $stmt->execute([
            'calendario_alumnos' => $calendarioId,
            'calendario_comision' => $calendarioId,
        ]);

        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['plan_label'] = trim(implode(' ', array_filter([
                $this->acronym((string) ($row['plan_orientacion'] ?? '')),
                (string) ($row['plan_resolucion'] ?? ''),
            ])));
        }

        return $rows;
    }

    private function acronym(string $value): string
    {
        $words = preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return implode('', array_map(static fn (string $word): string => substr($word, 0, 1), $words));
    }

    private function remotePersonaData(array $remote): array
    {
        $nullable = static fn (mixed $value): mixed => trim((string) ($value ?? '')) === '' ? null : trim((string) $value);
        $integer = static fn (mixed $value): ?int => trim((string) ($value ?? '')) === '' ? null : (int) $value;
        $integerInRange = static function (mixed $value, int $minimum, int $maximum) use ($integer): ?int {
            $parsed = $integer($value);
            return $parsed !== null && $parsed >= $minimum && $parsed <= $maximum ? $parsed : null;
        };

        return [
            'nombres' => $nullable($remote['nombre'] ?? null) ?? 'Sin nombre',
            'apellidos' => $nullable($remote['apellido'] ?? null),
            'numero_documento' => preg_replace('/\D+/', '', (string) ($remote['dni'] ?? $remote['numero_documento'] ?? '')),
            'cuil1' => $integerInRange($remote['cuil1'] ?? null, 0, 99),
            'cuil2' => $integerInRange($remote['cuil2'] ?? null, 0, 9),
            'sexo' => $integerInRange($remote['sexo'] ?? null, 1, 3),
            'dia_nacimiento' => $integerInRange($remote['dia_nac'] ?? null, 1, 31),
            'mes_nacimiento' => $integerInRange($remote['mes_nac'] ?? null, 1, 12),
            'anio_nacimiento' => $integerInRange($remote['ano_nac'] ?? null, 1900, (int) date('Y')),
            'telefono' => $nullable($remote['nro_telefono'] ?? $remote['telefono'] ?? null),
            'codigo_area' => $nullable($remote['cod_area'] ?? null),
            'email' => $nullable($remote['email'] ?? null),
            'nacionalidad' => $nullable($remote['nacionalidad'] ?? null),
            'descripcion_domicilio' => $nullable($remote['direccion'] ?? null),
            'departamento' => $nullable($remote['departamento'] ?? null),
            'localidad' => $nullable($remote['localidad'] ?? null),
            'partido' => $nullable($remote['partido'] ?? null),
        ];
    }

    private function tramoIngresoShort(array $alumno): string
    {
        $anio = trim((string) ($alumno['anio_ingreso'] ?? ''));
        if ($anio === '') {
            return '11';
        }

        $semestre = trim((string) ($alumno['semestre_ingreso'] ?? ''));

        return $anio . ($semestre !== '' ? $semestre : '1');
    }

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
        $sorts = [
            'nombre' => "sede_nombre {$direction}, comision.pfid ASC",
            'pfid' => "CAST(comision.pfid AS UNSIGNED) {$direction}, comision.pfid {$direction}",
            'planificacion' => "CAST(planificacion.anio AS UNSIGNED) {$direction}, CAST(planificacion.semestre AS UNSIGNED) {$direction}, plan.orientacion {$direction}, plan.resolucion {$direction}, comision.pfid ASC",
            'apertura' => "comision.apertura {$direction}, comision.pfid ASC",
            'turno' => "comision.turno {$direction}, comision.pfid ASC",
        ];

        return $sorts[$sort] ?? $sorts['pfid'];
    }
}
