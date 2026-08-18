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
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
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
     * Genera (o reutiliza) la comisión del tramo siguiente y asegura sus cursos,
     * como script/generar_comision_siguiente.php del sistema anterior.
     *
     * @return array{
     *   id: string,
     *   created: bool,
     *   cursos_creados: int,
     *   calendario_id: string,
     *   planificacion_id: string
     * }
     */
    public function generarSiguiente(string $comisionId, ?string $calendarioDestinoId = null): array
    {
        $comisionId = trim($comisionId);
        if ($comisionId === '') {
            throw new \InvalidArgumentException('Falta el id de la comisión.');
        }

        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.turno,
                   comision.division,
                   comision.identificacion,
                   comision.autorizada,
                   comision.apertura,
                   comision.publicada,
                   comision.pfid,
                   comision.sede,
                   comision.modalidad,
                   comision.planificacion,
                   comision.comision_siguiente,
                   comision.calendario,
                   planificacion.plan AS plan_id,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
                   calendario.anio AS calendario_anio,
                   calendario.semestre AS calendario_semestre
            FROM comision
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN calendario ON calendario.id = comision.calendario
            WHERE comision.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $comisionId]);
        $comision = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($comision === false) {
            throw new \RuntimeException('La comisión no existe.');
        }

        $planId = trim((string) ($comision['plan_id'] ?? ''));
        $anio = (int) ($comision['planificacion_anio'] ?? 0);
        $semestre = (int) ($comision['planificacion_semestre'] ?? 0);
        if ($planId === '' || $anio < 1 || $semestre < 1) {
            throw new \RuntimeException('La comisión no tiene una planificación válida.');
        }

        $tramoSiguiente = $this->tramoSiguiente($anio, $semestre);
        if ($tramoSiguiente === null) {
            throw new \RuntimeException('No hay tramo siguiente (la planificación ya es 3° año / 2° semestre).');
        }

        $planificacionStmt = $this->pdo->prepare("
            SELECT id
            FROM planificacion
            WHERE plan = :plan
              AND anio = :anio
              AND semestre = :semestre
            LIMIT 1
        ");
        $planificacionStmt->execute([
            'plan' => $planId,
            'anio' => $tramoSiguiente['anio'],
            'semestre' => $tramoSiguiente['semestre'],
        ]);
        $nuevaPlanificacionId = $planificacionStmt->fetchColumn();
        if ($nuevaPlanificacionId === false || $nuevaPlanificacionId === null || $nuevaPlanificacionId === '') {
            throw new \RuntimeException(
                "No se encontró planificación para el plan {$planId} tramo {$tramoSiguiente['anio']}/{$tramoSiguiente['semestre']}.",
            );
        }
        $nuevaPlanificacionId = (string) $nuevaPlanificacionId;

        $this->pdo->beginTransaction();
        try {
            $created = false;
            $siguienteId = trim((string) ($comision['comision_siguiente'] ?? ''));

            if ($siguienteId !== '') {
                $siguienteStmt = $this->pdo->prepare("
                    SELECT id, planificacion
                    FROM comision
                    WHERE id = :id
                    LIMIT 1
                ");
                $siguienteStmt->execute(['id' => $siguienteId]);
                $siguiente = $siguienteStmt->fetch(PDO::FETCH_ASSOC);
                if ($siguiente === false) {
                    throw new \RuntimeException('La comisión siguiente referenciada no existe.');
                }
                if ((string) ($siguiente['planificacion'] ?? '') !== $nuevaPlanificacionId) {
                    throw new \RuntimeException(
                        'La comisión ya tiene siguiente, pero su planificación no coincide con el tramo esperado.',
                    );
                }
            } else {
                $calendarioDestino = $this->resolveCalendarioDestino(
                    (string) ($comision['calendario'] ?? ''),
                    (int) ($comision['calendario_anio'] ?? 0),
                    (int) ($comision['calendario_semestre'] ?? 0),
                    $calendarioDestinoId,
                );

                $siguienteId = uniqid();
                $insert = $this->pdo->prepare("
                    INSERT INTO comision (
                        id, calendario, sede, modalidad, planificacion, turno, division,
                        identificacion, pfid, autorizada, apertura, publicada
                    ) VALUES (
                        :id, :calendario, :sede, :modalidad, :planificacion, :turno, :division,
                        :identificacion, :pfid, 1, 0, 0
                    )
                ");
                $insert->execute([
                    'id' => $siguienteId,
                    'calendario' => $calendarioDestino,
                    'sede' => $comision['sede'],
                    'modalidad' => $comision['modalidad'],
                    'planificacion' => $nuevaPlanificacionId,
                    'turno' => $comision['turno'],
                    'division' => $comision['division'] !== null && $comision['division'] !== ''
                        ? $comision['division']
                        : '-',
                    'identificacion' => $comision['identificacion'],
                    'pfid' => $comision['pfid'],
                ]);

                $link = $this->pdo->prepare('
                    UPDATE comision
                    SET comision_siguiente = :siguiente
                    WHERE id = :id
                ');
                $link->execute([
                    'siguiente' => $siguienteId,
                    'id' => $comisionId,
                ]);
                $created = true;
            }

            $cursosCreados = $this->ensureCursosForPlanificacion($siguienteId, $nuevaPlanificacionId);

            $calendarioFinalStmt = $this->pdo->prepare('SELECT calendario FROM comision WHERE id = :id LIMIT 1');
            $calendarioFinalStmt->execute(['id' => $siguienteId]);
            $calendarioFinal = (string) ($calendarioFinalStmt->fetchColumn() ?: '');

            $this->pdo->commit();

            return [
                'id' => $siguienteId,
                'created' => $created,
                'cursos_creados' => $cursosCreados,
                'calendario_id' => $calendarioFinal,
                'planificacion_id' => $nuevaPlanificacionId,
            ];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    /**
     * Copia los alumnos activos de la comisión a su comisión siguiente
     * (como script/transferir_alumnos_activos.php, para una sola comisión).
     * No duplica alumnos que ya estén en la siguiente.
     *
     * @return array{
     *   comision_siguiente_id: string,
     *   activos_origen: int,
     *   ya_en_siguiente: int,
     *   transferidos: int
     * }
     */
    public function transferirAlumnosActivos(string $comisionId): array
    {
        $comisionId = trim($comisionId);
        if ($comisionId === '') {
            throw new \InvalidArgumentException('Falta el id de la comisión.');
        }

        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.comision_siguiente,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre
            FROM comision
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            WHERE comision.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $comisionId]);
        $comision = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($comision === false) {
            throw new \RuntimeException('La comisión no existe.');
        }

        $siguienteId = trim((string) ($comision['comision_siguiente'] ?? ''));
        if ($siguienteId === '') {
            throw new \RuntimeException('La comisión no tiene comisión siguiente. Generala antes de transferir alumnos.');
        }

        $anio = (string) ($comision['planificacion_anio'] ?? '');
        $semestre = (string) ($comision['planificacion_semestre'] ?? '');
        if ($anio . $semestre === '32') {
            throw new \RuntimeException('No se transferen alumnos desde una comisión de tramo 3-2 (egreso).');
        }

        $checkSiguiente = $this->pdo->prepare('SELECT id FROM comision WHERE id = :id LIMIT 1');
        $checkSiguiente->execute(['id' => $siguienteId]);
        if ($checkSiguiente->fetchColumn() === false) {
            throw new \RuntimeException('La comisión siguiente referenciada no existe.');
        }

        $activosStmt = $this->pdo->prepare("
            SELECT alumno
            FROM alumno_comision
            WHERE comision = :comision
              AND activo = 1
        ");
        $activosStmt->execute(['comision' => $comisionId]);
        $alumnosActivos = array_values(array_unique(array_map(
            static fn (mixed $id): string => (string) $id,
            $activosStmt->fetchAll(PDO::FETCH_COLUMN),
        )));

        $existentesStmt = $this->pdo->prepare("
            SELECT alumno
            FROM alumno_comision
            WHERE comision = :comision
        ");
        $existentesStmt->execute(['comision' => $siguienteId]);
        $yaEnSiguiente = array_fill_keys(array_map(
            static fn (mixed $id): string => (string) $id,
            $existentesStmt->fetchAll(PDO::FETCH_COLUMN),
        ), true);

        $insert = $this->pdo->prepare("
            INSERT INTO alumno_comision (id, alumno, comision, estado, activo)
            VALUES (:id, :alumno, :comision, 'Regular', 1)
        ");

        $this->pdo->beginTransaction();
        try {
            $transferidos = 0;
            $yaEstaban = 0;
            foreach ($alumnosActivos as $alumnoId) {
                if (isset($yaEnSiguiente[$alumnoId])) {
                    $yaEstaban++;
                    continue;
                }
                $insert->execute([
                    'id' => uniqid(),
                    'alumno' => $alumnoId,
                    'comision' => $siguienteId,
                ]);
                $yaEnSiguiente[$alumnoId] = true;
                $transferidos++;
            }
            $this->pdo->commit();

            return [
                'comision_siguiente_id' => $siguienteId,
                'activos_origen' => count($alumnosActivos),
                'ya_en_siguiente' => $yaEstaban,
                'transferidos' => $transferidos,
            ];
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    /**
     * Reactiva o desactiva alumnos de la comisión según calificaciones aprobadas
     * del mismo año/semestre que comision.planificacion.
     * ≥ 3 aprobadas (nota_final ≥ 7 o crec ≥ 4) → activo = 1; si no → activo = 0.
     *
     * @return array{
     *   total: int,
     *   activados: int,
     *   desactivados: int,
     *   sin_cambio: int,
     *   planificacion_anio: string,
     *   planificacion_semestre: string
     * }
     */
    public function reactivarAlumnos(string $comisionId): array
    {
        $comisionId = trim($comisionId);
        if ($comisionId === '') {
            throw new \InvalidArgumentException('Falta el id de la comisión.');
        }

        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre
            FROM comision
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            WHERE comision.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $comisionId]);
        $comision = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($comision === false) {
            throw new \RuntimeException('La comisión no existe.');
        }

        $anio = trim((string) ($comision['planificacion_anio'] ?? ''));
        $semestre = trim((string) ($comision['planificacion_semestre'] ?? ''));
        if ($anio === '' || $semestre === '') {
            throw new \RuntimeException('La comisión no tiene planificación (año/semestre) configurada.');
        }

        $conteoStmt = $this->pdo->prepare("
            SELECT alumno_comision.id AS alumno_comision_id,
                   alumno_comision.activo AS activo_actual,
                   COALESCE(aprobadas.cantidad, 0) AS cantidad_aprobadas
            FROM alumno_comision
            LEFT JOIN (
                SELECT calificacion.alumno AS alumno_id,
                       COUNT(DISTINCT calificacion.disposicion) AS cantidad
                FROM calificacion
                INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
                INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
                INNER JOIN alumno_comision ac_filtro
                        ON ac_filtro.alumno = calificacion.alumno
                       AND ac_filtro.comision = :comision_filtro
                WHERE planificacion.anio = :anio
                  AND planificacion.semestre = :semestre
                  AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                GROUP BY calificacion.alumno
            ) aprobadas ON aprobadas.alumno_id = alumno_comision.alumno
            WHERE alumno_comision.comision = :comision_id
        ");
        $conteoStmt->execute([
            'comision_filtro' => $comisionId,
            'comision_id' => $comisionId,
            'anio' => $anio,
            'semestre' => $semestre,
        ]);
        $filas = $conteoStmt->fetchAll(PDO::FETCH_ASSOC);

        $update = $this->pdo->prepare('
            UPDATE alumno_comision
            SET activo = :activo
            WHERE id = :id
        ');

        $activados = 0;
        $desactivados = 0;
        $sinCambio = 0;

        $this->pdo->beginTransaction();
        try {
            foreach ($filas as $fila) {
                $cantidad = (int) ($fila['cantidad_aprobadas'] ?? 0);
                $nuevoActivo = $cantidad >= 3 ? 1 : 0;
                $activoActual = (int) ($fila['activo_actual'] ?? 0);

                if ($activoActual === $nuevoActivo) {
                    $sinCambio++;
                    continue;
                }

                $update->execute([
                    'activo' => $nuevoActivo,
                    'id' => (string) $fila['alumno_comision_id'],
                ]);

                if ($nuevoActivo === 1) {
                    $activados++;
                } else {
                    $desactivados++;
                }
            }
            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }

        return [
            'total' => count($filas),
            'activados' => $activados,
            'desactivados' => $desactivados,
            'sin_cambio' => $sinCambio,
            'planificacion_anio' => $anio,
            'planificacion_semestre' => $semestre,
        ];
    }

    /**
     * @return array{anio: string, semestre: string}|null
     */
    private function tramoSiguiente(int $anio, int $semestre): ?array
    {
        if ($semestre === 2) {
            if ($anio === 3) {
                return null;
            }
            $anio++;
            $semestre = 1;
        } else {
            $semestre = 2;
        }

        return [
            'anio' => (string) $anio,
            'semestre' => (string) $semestre,
        ];
    }

    /**
     * Calendario destino: override explícito, o el período siguiente al de la comisión origen.
     */
    private function resolveCalendarioDestino(
        string $calendarioOrigenId,
        int $calendarioAnio,
        int $calendarioSemestre,
        ?string $overrideId,
    ): string {
        $overrideId = $overrideId !== null ? trim($overrideId) : '';
        if ($overrideId !== '') {
            $check = $this->pdo->prepare('SELECT id FROM calendario WHERE id = :id LIMIT 1');
            $check->execute(['id' => $overrideId]);
            $found = $check->fetchColumn();
            if ($found === false || $found === null || $found === '') {
                throw new \RuntimeException('El calendario destino indicado no existe.');
            }

            return (string) $found;
        }

        if ($calendarioAnio < 1 || $calendarioSemestre < 1) {
            throw new \RuntimeException('La comisión no tiene un calendario válido para calcular el destino.');
        }

        if ($calendarioSemestre === 1) {
            $nextAnio = $calendarioAnio;
            $nextSemestre = 2;
        } else {
            $nextAnio = $calendarioAnio + 1;
            $nextSemestre = 1;
        }

        $stmt = $this->pdo->prepare("
            SELECT id
            FROM calendario
            WHERE anio = :anio
              AND semestre = :semestre
            ORDER BY inicio DESC, id DESC
            LIMIT 1
        ");
        $stmt->execute([
            'anio' => $nextAnio,
            'semestre' => $nextSemestre,
        ]);
        $id = $stmt->fetchColumn();
        if ($id === false || $id === null || $id === '') {
            throw new \RuntimeException(
                "No hay calendario cargado para el período {$nextAnio}-{$nextSemestre}. Creá el calendario o indicá CALENDARIO_ID_ACTUAL.",
            );
        }

        if ((string) $id === $calendarioOrigenId) {
            throw new \RuntimeException('El calendario destino coincide con el de origen; revisá los calendarios cargados.');
        }

        return (string) $id;
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
        $disposicionId = $this->nullableText($data['disposicion'] ?? null);
        $horas = (int) ($data['horas_catedra'] ?? 0);
        if ($horas <= 0 && $disposicionId !== null) {
            $horas = $this->horasCatedraDisposicion($disposicionId);
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO curso (id, comision, disposicion, horas_catedra, descripcion_horario)
            VALUES (:id, :comision, :disposicion, :horas_catedra, :descripcion_horario)
        ");
        $stmt->execute([
            'id' => $id,
            'comision' => $comisionId,
            'disposicion' => $disposicionId,
            'horas_catedra' => $horas,
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

        $this->rellenarHorasCatedraDesdeDisposicion($comisionId);

        return $created;
    }

    private function horasCatedraDisposicion(string $disposicionId): int
    {
        $stmt = $this->pdo->prepare('SELECT horas_catedra FROM disposicion WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $disposicionId]);
        $value = $stmt->fetchColumn();

        return $value === false ? 0 : (int) $value;
    }

    private function rellenarHorasCatedraDesdeDisposicion(string $comisionId): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE curso
            INNER JOIN disposicion ON disposicion.id = curso.disposicion
            SET curso.horas_catedra = disposicion.horas_catedra
            WHERE curso.comision = :comision
              AND (curso.horas_catedra IS NULL OR curso.horas_catedra = 0)
              AND disposicion.horas_catedra > 0
        ");
        $stmt->execute(['comision' => $comisionId]);

        return $stmt->rowCount();
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Rindex de comisión: matriz alumnos × cursos (notas aprobadas por disposición).
     * Equivalente a version 5/wp/rdc_rindex_comision.
     *
     * @return array{
     *   comision: array<string, mixed>,
     *   columnas: list<array{curso_id: string, disposicion_id: string, asignatura: string, tramo: string, docente: string, semestre: string}>,
     *   filas: list<array{
     *     alumno_id: string,
     *     persona_id: string,
     *     apellidos: string,
     *     nombres: string,
     *     numero_documento: string,
     *     activo: int,
     *     notas: list<string>
     *   }>
     * }|null
     */
    public function rindex(string $comisionId): ?array
    {
        $comision = $this->byId($comisionId);
        if ($comision === null) {
            return null;
        }

        $columnasStmt = $this->pdo->prepare("
            SELECT curso.id AS curso_id,
                   curso.disposicion AS disposicion_id,
                   COALESCE(asignatura.nombre, '?') AS asignatura,
                   COALESCE(asignatura.codigo, '') AS asignatura_codigo,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
                   TRIM(CONCAT(
                       COALESCE(planificacion.anio, '?'),
                       '°',
                       COALESCE(planificacion.semestre, '?'),
                       'C'
                   )) AS tramo,
                   TRIM(CONCAT_WS(' ', NULLIF(docente.apellidos, ''), NULLIF(docente.nombres, ''))) AS docente
            FROM curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN (
                SELECT toma.curso, MIN(toma.id) AS toma_id
                FROM toma
                INNER JOIN curso curso_toma ON curso_toma.id = toma.curso
                WHERE curso_toma.comision = :comision_tomas
                  AND toma.estado = 'Aprobada'
                  AND toma.estado_contralor = 'Pasar'
                GROUP BY toma.curso
            ) toma_por_curso ON toma_por_curso.curso = curso.id
            LEFT JOIN toma toma_activa ON toma_activa.id = toma_por_curso.toma_id
            LEFT JOIN persona docente ON docente.id = toma_activa.docente
            WHERE curso.comision = :comision_id
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     asignatura.nombre ASC,
                     curso.id ASC
        ");
        $columnasStmt->execute([
            'comision_tomas' => $comisionId,
            'comision_id' => $comisionId,
        ]);
        $cursos = $columnasStmt->fetchAll(PDO::FETCH_ASSOC);

        $columnas = [];
        $disposicionIds = [];
        foreach ($cursos as $curso) {
            $disposicionId = trim((string) ($curso['disposicion_id'] ?? ''));
            if ($disposicionId !== '') {
                $disposicionIds[$disposicionId] = true;
            }
            $asignatura = trim(implode(' ', array_filter([
                (string) ($curso['asignatura'] ?? ''),
                (string) ($curso['asignatura_codigo'] ?? ''),
            ])));
            $columnas[] = [
                'curso_id' => (string) ($curso['curso_id'] ?? ''),
                'disposicion_id' => $disposicionId,
                'asignatura' => $asignatura !== '' ? $asignatura : '?',
                'tramo' => (string) ($curso['tramo'] ?? '?'),
                'docente' => trim((string) ($curso['docente'] ?? '')) !== ''
                    ? trim((string) $curso['docente'])
                    : '?',
                'semestre' => (string) ($curso['planificacion_semestre'] ?? ''),
            ];
        }

        $alumnosStmt = $this->pdo->prepare("
            SELECT alumno_comision.activo,
                   alumno.id AS alumno_id,
                   alumno.persona AS persona_id,
                   persona.apellidos,
                   persona.nombres,
                   persona.numero_documento
            FROM alumno_comision
            INNER JOIN alumno ON alumno.id = alumno_comision.alumno
            INNER JOIN persona ON persona.id = alumno.persona
            WHERE alumno_comision.comision = :comision_id
            ORDER BY alumno_comision.activo DESC,
                     persona.apellidos ASC,
                     persona.nombres ASC
        ");
        $alumnosStmt->execute(['comision_id' => $comisionId]);
        $alumnos = $alumnosStmt->fetchAll(PDO::FETCH_ASSOC);

        $notasPorAlumnoDisp = [];
        $alumnoIds = array_values(array_unique(array_filter(array_map(
            static fn (array $row): string => trim((string) ($row['alumno_id'] ?? '')),
            $alumnos,
        ))));
        $disposicionList = array_keys($disposicionIds);

        if ($alumnoIds !== [] && $disposicionList !== []) {
            $alumnoPlaceholders = [];
            $dispPlaceholders = [];
            $params = [];
            foreach ($alumnoIds as $index => $alumnoId) {
                $key = 'a' . $index;
                $alumnoPlaceholders[] = ':' . $key;
                $params[$key] = $alumnoId;
            }
            foreach ($disposicionList as $index => $disposicionId) {
                $key = 'd' . $index;
                $dispPlaceholders[] = ':' . $key;
                $params[$key] = $disposicionId;
            }

            $califStmt = $this->pdo->prepare('
                SELECT calificacion.alumno,
                       calificacion.disposicion,
                       calificacion.nota_final,
                       calificacion.crec
                FROM calificacion
                WHERE calificacion.alumno IN (' . implode(', ', $alumnoPlaceholders) . ')
                  AND calificacion.disposicion IN (' . implode(', ', $dispPlaceholders) . ')
                  AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
            ');
            $califStmt->execute($params);
            foreach ($califStmt->fetchAll(PDO::FETCH_ASSOC) as $calificacion) {
                $alumnoId = (string) ($calificacion['alumno'] ?? '');
                $disposicionId = (string) ($calificacion['disposicion'] ?? '');
                $nota = $this->formatNotaAprobada(
                    $calificacion['nota_final'] ?? null,
                    $calificacion['crec'] ?? null,
                );
                if ($alumnoId === '' || $disposicionId === '' || $nota === null) {
                    continue;
                }
                $notasPorAlumnoDisp[$alumnoId][$disposicionId] = $nota;
            }
        }

        $filas = [];
        foreach ($alumnos as $alumno) {
            $alumnoId = (string) ($alumno['alumno_id'] ?? '');
            $notas = [];
            foreach ($columnas as $columna) {
                $disposicionId = $columna['disposicion_id'];
                $notas[] = $disposicionId !== ''
                    ? (string) ($notasPorAlumnoDisp[$alumnoId][$disposicionId] ?? '')
                    : '';
            }
            $filas[] = [
                'alumno_id' => $alumnoId,
                'persona_id' => (string) ($alumno['persona_id'] ?? ''),
                'apellidos' => (string) ($alumno['apellidos'] ?? ''),
                'nombres' => (string) ($alumno['nombres'] ?? ''),
                'numero_documento' => (string) ($alumno['numero_documento'] ?? ''),
                'activo' => (int) ($alumno['activo'] ?? 0),
                'notas' => $notas,
            ];
        }

        return [
            'comision' => $comision,
            'columnas' => $columnas,
            'filas' => $filas,
        ];
    }

    private function formatNotaAprobada(mixed $notaFinal, mixed $crec): ?string
    {
        if ($notaFinal !== null && $notaFinal !== '' && (float) $notaFinal >= 7) {
            return (string) (int) round((float) $notaFinal);
        }
        if ($crec !== null && $crec !== '' && (float) $crec >= 4) {
            return (string) (int) round((float) $crec) . 'c';
        }

        return null;
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
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   comision.autorizada,
                   comision.apertura,
                   comision.turno,
                   COALESCE(alumnos.cantidad_alumnos, 0) AS cantidad_alumnos,
                   COALESCE(alumnos.cantidad_alumnos_activos, 0) AS cantidad_alumnos_activos,
                   comision.comision_siguiente AS comision_siguiente_id,
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
