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
                   comision.division,
                   comision.autorizada,
                   comision.apertura,
                   comision.publicada,
                   comision.observaciones,
                   comision.calendario AS calendario_id,
                   comision.sede AS sede_id,
                   comision.modalidad AS modalidad_id,
                   comision.planificacion AS planificacion_id,
                   comision.comision_siguiente,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   calendario.descripcion AS calendario_descripcion,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS planificacion_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   modalidad.nombre AS modalidad_nombre
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN modalidad ON modalidad.id = comision.modalidad
            WHERE comision.id = :comision_id
            LIMIT 1
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /** @return list<array{id: string, label: string}> */
    public function modalidadesOptions(): array
    {
        $rows = $this->pdo
            ->query('SELECT id, nombre FROM modalidad ORDER BY nombre ASC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): array => [
            'id' => (string) $row['id'],
            'label' => (string) ($row['nombre'] ?? $row['id']),
        ], $rows);
    }

    /** @return list<array{id: string, label: string}> */
    public function sedesOptions(): array
    {
        $rows = $this->pdo
            ->query("
                SELECT id, numero, nombre
                FROM sede
                ORDER BY CAST(numero AS UNSIGNED) ASC, numero ASC, nombre ASC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $label = trim(implode(' - ', array_filter([
                (string) ($row['numero'] ?? ''),
                (string) ($row['nombre'] ?? ''),
            ])));

            return [
                'id' => (string) $row['id'],
                'label' => $label !== '' ? $label : (string) $row['id'],
            ];
        }, $rows);
    }

    /** @return list<array{id: string, label: string}> */
    public function planificacionesOptions(): array
    {
        $rows = $this->pdo
            ->query("
                SELECT planificacion.id,
                       planificacion.anio,
                       planificacion.semestre,
                       plan.orientacion,
                       plan.resolucion
                FROM planificacion
                LEFT JOIN plan ON plan.id = planificacion.plan
                ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                         CAST(planificacion.semestre AS UNSIGNED) ASC,
                         plan.orientacion ASC,
                         plan.resolucion ASC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $label = trim(implode(' ', array_filter([
                (string) ($row['orientacion'] ?? ''),
                (string) ($row['resolucion'] ?? ''),
                trim(($row['anio'] ?? '') . '/' . ($row['semestre'] ?? ''), '/'),
            ])));

            return [
                'id' => (string) $row['id'],
                'label' => $label !== '' ? $label : (string) $row['id'],
            ];
        }, $rows);
    }

    /**
     * Disposiciones de planes vigentes (misma lista que el admin legacy).
     *
     * @return list<array{id: string, label: string, horas_catedra: int}>
     */
    public function disposicionesOptions(): array
    {
        $stmt = $this->pdo->query("
            SELECT disposicion.id,
                   disposicion.horas_catedra,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   planificacion.anio,
                   planificacion.semestre,
                   plan.resolucion,
                   plan.orientacion
            FROM disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            INNER JOIN plan ON plan.id = planificacion.plan
            WHERE plan.id IN ('202303101', '202303102', '4', '5', '2026032201')
            ORDER BY asignatura.nombre ASC,
                     CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     plan.resolucion ASC,
                     plan.orientacion ASC
        ");

        return array_map(function (array $row): array {
            $acronym = $this->acronym((string) ($row['orientacion'] ?? ''));
            $label = trim(implode(' ', array_filter([
                (string) ($row['asignatura_nombre'] ?? ''),
                (string) ($row['asignatura_codigo'] ?? ''),
                trim(($row['anio'] ?? '') . '/' . ($row['semestre'] ?? ''), '/'),
                (string) ($row['resolucion'] ?? ''),
                $acronym,
                '(' . (string) ($row['horas_catedra'] ?? '?') . ')',
            ])));

            return [
                'id' => (string) $row['id'],
                'label' => $label !== '' ? $label : (string) $row['id'],
                'horas_catedra' => (int) ($row['horas_catedra'] ?? 0),
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function cursosByComision(string $comisionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT curso.id,
                   curso.horas_catedra,
                   curso.descripcion_horario,
                   curso.disposicion AS disposicion_id,
                   curso.codigo,
                   disposicion.horas_catedra AS disposicion_horas_catedra,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.resolucion AS plan_resolucion,
                   plan.orientacion AS plan_orientacion
            FROM curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE curso.comision = :comision_id
            ORDER BY asignatura.nombre ASC, curso.id ASC
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['label'] = trim(implode(' ', array_filter([
                (string) ($row['asignatura_nombre'] ?? ''),
                (string) ($row['asignatura_codigo'] ?? ''),
                (string) ($row['tramo_label'] ?? ''),
                (string) ($row['plan_resolucion'] ?? ''),
                $this->acronym((string) ($row['plan_orientacion'] ?? '')),
            ])));
        }
        unset($row);

        return $rows;
    }

    /**
     * Crea una comisión nueva (vacía de cursos/tomas) y opcionalmente genera cursos de la planificación.
     *
     * @param array{
     *   calendario: string,
     *   sede: string,
     *   modalidad: string,
     *   planificacion: ?string,
     *   turno: ?string,
     *   division: string,
     *   pfid: ?string,
     *   autorizada: int,
     *   apertura: int,
     *   publicada: int,
     *   observaciones: ?string,
     *   comision_siguiente: ?string
     * } $data
     * @return array{id: string, cursos_creados: int}
     */
    public function createAdmin(array $data): array
    {
        $comisionId = uniqid();
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO comision (
                    id, calendario, sede, modalidad, planificacion, turno, division, pfid,
                    autorizada, apertura, publicada, observaciones, comision_siguiente
                ) VALUES (
                    :id, :calendario, :sede, :modalidad, :planificacion, :turno, :division, :pfid,
                    :autorizada, :apertura, :publicada, :observaciones, :comision_siguiente
                )
            ");
            $stmt->execute([
                'id' => $comisionId,
                'calendario' => $data['calendario'],
                'sede' => $data['sede'],
                'modalidad' => $data['modalidad'],
                'planificacion' => $data['planificacion'],
                'turno' => $data['turno'],
                'division' => $data['division'],
                'pfid' => $data['pfid'],
                'autorizada' => $data['autorizada'],
                'apertura' => $data['apertura'],
                'publicada' => $data['publicada'],
                'observaciones' => $data['observaciones'],
                'comision_siguiente' => $data['comision_siguiente'],
            ]);

            $cursosCreados = 0;
            $planificacionId = trim((string) ($data['planificacion'] ?? ''));
            if ($planificacionId !== '') {
                $cursosCreados = $this->ensureCursosForPlanificacion($comisionId, $planificacionId);
            }

            $this->pdo->commit();

            return ['id' => $comisionId, 'cursos_creados' => $cursosCreados];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    /**
     * Actualiza la comisión y crea cursos faltantes de la planificación (como el script legacy).
     *
     * @param array{
     *   calendario: string,
     *   sede: string,
     *   modalidad: string,
     *   planificacion: ?string,
     *   turno: ?string,
     *   division: string,
     *   pfid: ?string,
     *   autorizada: int,
     *   apertura: int,
     *   publicada: int,
     *   observaciones: ?string,
     *   comision_siguiente: ?string
     * } $data
     * @return array{cursos_creados: int}
     */
    public function updateAdmin(string $comisionId, array $data): array
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("
                UPDATE comision
                SET calendario = :calendario,
                    sede = :sede,
                    modalidad = :modalidad,
                    planificacion = :planificacion,
                    turno = :turno,
                    division = :division,
                    pfid = :pfid,
                    autorizada = :autorizada,
                    apertura = :apertura,
                    publicada = :publicada,
                    observaciones = :observaciones,
                    comision_siguiente = :comision_siguiente
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $comisionId,
                'calendario' => $data['calendario'],
                'sede' => $data['sede'],
                'modalidad' => $data['modalidad'],
                'planificacion' => $data['planificacion'],
                'turno' => $data['turno'],
                'division' => $data['division'],
                'pfid' => $data['pfid'],
                'autorizada' => $data['autorizada'],
                'apertura' => $data['apertura'],
                'publicada' => $data['publicada'],
                'observaciones' => $data['observaciones'],
                'comision_siguiente' => $data['comision_siguiente'],
            ]);

            $cursosCreados = 0;
            $planificacionId = trim((string) ($data['planificacion'] ?? ''));
            if ($planificacionId !== '') {
                $cursosCreados = $this->ensureCursosForPlanificacion($comisionId, $planificacionId);
            }

            $this->pdo->commit();

            return ['cursos_creados' => $cursosCreados];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    /**
     * Elimina la comisión y sus cursos solo si no hay tomas, alumnos ni referencias como comisión siguiente.
     *
     * @return array{calendario_id: string, cursos_eliminados: int}
     */
    public function deleteIfAllowed(string $comisionId): array
    {
        $comisionId = trim($comisionId);
        if ($comisionId === '') {
            throw new \InvalidArgumentException('Falta el id de la comisión.');
        }

        $comision = $this->byId($comisionId);
        if ($comision === null) {
            throw new \RuntimeException('La comisión no existe.');
        }

        $bloqueos = $this->deleteBlockers($comisionId);
        if ($bloqueos !== []) {
            throw new \RuntimeException('No se puede eliminar la comisión: ' . implode(' ', $bloqueos));
        }

        $this->pdo->beginTransaction();
        try {
            $deleteCursos = $this->pdo->prepare('DELETE FROM curso WHERE comision = :comision');
            $deleteCursos->execute(['comision' => $comisionId]);
            $cursosEliminados = $deleteCursos->rowCount();

            $deleteComision = $this->pdo->prepare('DELETE FROM comision WHERE id = :id');
            $deleteComision->execute(['id' => $comisionId]);
            if ($deleteComision->rowCount() === 0) {
                throw new \RuntimeException('No se pudo eliminar la comisión.');
            }

            $this->pdo->commit();

            return [
                'calendario_id' => (string) ($comision['calendario_id'] ?? ''),
                'cursos_eliminados' => $cursosEliminados,
            ];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    /**
     * Motivos por los que no se puede borrar (vacío = se puede borrar).
     *
     * @return list<string>
     */
    public function deleteBlockers(string $comisionId): array
    {
        $blockers = [];

        $tomasStmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            WHERE curso.comision = :comision_id
        ");
        $tomasStmt->execute(['comision_id' => $comisionId]);
        $tomas = (int) $tomasStmt->fetchColumn();
        if ($tomas > 0) {
            $blockers[] = "tiene {$tomas} toma(s).";
        }

        $alumnosStmt = $this->pdo->prepare('
            SELECT COUNT(*) FROM alumno_comision WHERE comision = :comision_id
        ');
        $alumnosStmt->execute(['comision_id' => $comisionId]);
        $alumnos = (int) $alumnosStmt->fetchColumn();
        if ($alumnos > 0) {
            $blockers[] = "tiene {$alumnos} alumno(s) asignado(s).";
        }

        $siguienteStmt = $this->pdo->prepare('
            SELECT COUNT(*) FROM comision WHERE comision_siguiente = :comision_id
        ');
        $siguienteStmt->execute(['comision_id' => $comisionId]);
        $siguientes = (int) $siguienteStmt->fetchColumn();
        if ($siguientes > 0) {
            $blockers[] = "es comisión siguiente de {$siguientes} otra(s) comisión(es).";
        }

        return $blockers;
    }

    public function updateCursos(string $comisionId, array $rows): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE curso
            SET disposicion = :disposicion,
                horas_catedra = :horas_catedra,
                descripcion_horario = :descripcion_horario
            WHERE id = :id AND comision = :comision
        ");

        foreach ($rows as $row) {
            $id = trim((string) ($row['id'] ?? ''));
            if ($id === '') {
                continue;
            }

            $stmt->execute([
                'id' => $id,
                'comision' => $comisionId,
                'disposicion' => $this->nullableText($row['disposicion'] ?? null),
                'horas_catedra' => (int) ($row['horas_catedra'] ?? 0),
                'descripcion_horario' => $this->nullableText($row['descripcion_horario'] ?? null),
            ]);
        }
    }

    public function addCurso(string $comisionId, array $data): string
    {
        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO curso (id, comision, disposicion, horas_catedra, descripcion_horario)
            VALUES (:id, :comision, :disposicion, :horas_catedra, :descripcion_horario)
        ");
        $stmt->execute([
            'id' => $id,
            'comision' => $comisionId,
            'disposicion' => $this->nullableText($data['disposicion'] ?? null),
            'horas_catedra' => (int) ($data['horas_catedra'] ?? 0),
            'descripcion_horario' => $this->nullableText($data['descripcion_horario'] ?? null),
        ]);

        return $id;
    }

    public function deleteCurso(string $comisionId, string $cursoId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM curso WHERE id = :id AND comision = :comision');
        $stmt->execute([
            'id' => $cursoId,
            'comision' => $comisionId,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException('No se encontró el curso para eliminar.');
        }
    }

    private function ensureCursosForPlanificacion(string $comisionId, string $planificacionId): int
    {
        $disposicionStmt = $this->pdo->prepare("
            SELECT id, horas_catedra
            FROM disposicion
            WHERE planificacion = :planificacion
        ");
        $disposicionStmt->execute(['planificacion' => $planificacionId]);
        $disposiciones = $disposicionStmt->fetchAll(PDO::FETCH_ASSOC);
        if ($disposiciones === []) {
            return 0;
        }

        $existentesStmt = $this->pdo->prepare("
            SELECT disposicion
            FROM curso
            WHERE comision = :comision
              AND disposicion IS NOT NULL
        ");
        $existentesStmt->execute(['comision' => $comisionId]);
        $existentes = array_flip(array_map('strval', $existentesStmt->fetchAll(PDO::FETCH_COLUMN)));

        $insert = $this->pdo->prepare("
            INSERT INTO curso (id, comision, disposicion, horas_catedra)
            VALUES (:id, :comision, :disposicion, :horas_catedra)
        ");

        $created = 0;
        foreach ($disposiciones as $disposicion) {
            $disposicionId = (string) $disposicion['id'];
            if (isset($existentes[$disposicionId])) {
                continue;
            }

            $insert->execute([
                'id' => uniqid(),
                'comision' => $comisionId,
                'disposicion' => $disposicionId,
                'horas_catedra' => (int) ($disposicion['horas_catedra'] ?? 0),
            ]);
            $created++;
        }

        return $created;
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);

        return $value === '' ? null : $value;
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
