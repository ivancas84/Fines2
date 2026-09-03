<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class InformeRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function egresadosPorCalendario(): array
    {
        $stmt = $this->pdo->query("
            SELECT calendario.anio,
                   calendario.semestre,
                   COUNT(DISTINCT alumnos_tramo_32.alumno) AS cantidad_egresados
            FROM (
                SELECT alumno_comision.alumno,
                       comision.calendario
                FROM alumno_comision
                INNER JOIN comision ON comision.id = alumno_comision.comision
                INNER JOIN planificacion comision_planificacion ON comision_planificacion.id = comision.planificacion
                WHERE comision_planificacion.anio = 3
                  AND comision_planificacion.semestre = 2
            ) alumnos_tramo_32
            INNER JOIN calendario ON calendario.id = alumnos_tramo_32.calendario
            INNER JOIN (
                SELECT calificacion.alumno
                FROM calificacion
                INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
                INNER JOIN planificacion disposicion_planificacion ON disposicion_planificacion.id = disposicion.planificacion
                WHERE disposicion_planificacion.anio = 3
                  AND disposicion_planificacion.semestre = 2
                  AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                GROUP BY calificacion.alumno
                HAVING COUNT(DISTINCT calificacion.disposicion) >= 5
            ) egresados ON egresados.alumno = alumnos_tramo_32.alumno
            GROUP BY calendario.anio, calendario.semestre
            ORDER BY calendario.anio DESC,
                     calendario.semestre DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Listado para copiar y pegar (migrado de version 5/scripts/contralor.php).
     *
     * Combinación calendario: tomas aprobadas con contralor "Pasar", sin planilla docente.
     * Combinación planilla: tomas asignadas a esa planilla docente.
     * El plan (comision.planificacion.plan) es un filtro opcional en ambos casos.
     *
     * @param array{calendario_id: ?string, planilla_id: ?string, plan_id: ?string} $filters
     * @return list<list<string>>
     */
    public function tomasContralor(array $filters): array
    {
        [$where, $params] = $this->contralorConditions($filters);

        $sql = "
            SELECT toma.fecha_toma,
                   toma.tipo_movimiento,
                   docente.nombres AS docente_nombres,
                   docente.apellidos AS docente_apellidos,
                   docente.numero_documento AS docente_documento,
                   docente.cuil AS docente_cuil,
                   docente.cuil1 AS docente_cuil1,
                   docente.cuil2 AS docente_cuil2,
                   docente.dia_nacimiento AS docente_dia_nacimiento,
                   docente.mes_nacimiento AS docente_mes_nacimiento,
                   docente.anio_nacimiento AS docente_anio_nacimiento,
                   plan.orientacion AS plan_orientacion,
                   asignatura.codigo AS asignatura_codigo,
                   curso.horas_catedra AS curso_horas_catedra,
                   disposicion.horas_catedra AS disposicion_horas_catedra,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
                   comision.turno,
                   calendario.fin AS calendario_fin
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            INNER JOIN comision ON comision.id = curso.comision
            INNER JOIN calendario ON calendario.id = comision.calendario
            INNER JOIN persona docente ON docente.id = toma.docente
            LEFT JOIN planificacion comision_planificacion ON comision_planificacion.id = comision.planificacion
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = COALESCE(disposicion.planificacion, comision.planificacion)
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE " . implode("\n              AND ", $where) . "
            ORDER BY docente.numero_documento ASC,
                     toma.fecha_toma ASC,
                     toma.id ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $rows = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $rows[] = $this->contralorCopyCells($row);
        }

        return $rows;
    }

    /**
     * Asigna una planilla docente a las tomas del listado de contralor sin planilla.
     *
     * @param array{calendario_id: ?string, plan_id: ?string} $filters
     */
    public function asignarPlanillaATomasSinPlanilla(string $planillaId, array $filters): int
    {
        $planillaId = trim($planillaId);
        if ($planillaId === '') {
            throw new \InvalidArgumentException('Seleccioná la planilla docente a asignar.');
        }

        $exists = $this->pdo->prepare('SELECT id FROM planilla_docente WHERE id = :id LIMIT 1');
        $exists->execute(['id' => $planillaId]);
        if ($exists->fetchColumn() === false) {
            throw new \RuntimeException('La planilla docente indicada no existe.');
        }

        $calendarioId = trim((string) ($filters['calendario_id'] ?? ''));
        if ($calendarioId === '') {
            throw new \InvalidArgumentException('La asignación solo aplica al listado por calendario, sin planilla.');
        }

        [$where, $params] = $this->contralorConditions([
            'calendario_id' => $calendarioId,
            'planilla_id' => null,
            'plan_id' => $filters['plan_id'] ?? null,
        ]);
        $params['asignar_planilla_id'] = $planillaId;

        $stmt = $this->pdo->prepare("
            UPDATE toma
            INNER JOIN curso ON curso.id = toma.curso
            INNER JOIN comision ON comision.id = curso.comision
            INNER JOIN persona docente ON docente.id = toma.docente
            LEFT JOIN planificacion comision_planificacion ON comision_planificacion.id = comision.planificacion
            SET toma.planilla_docente = :asignar_planilla_id
            WHERE " . implode("\n              AND ", $where) . "
        ");
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * @param array{calendario_id: ?string, planilla_id: ?string, plan_id: ?string} $filters
     * @return array{0: list<string>, 1: array<string, string>}
     */
    private function contralorConditions(array $filters): array
    {
        $calendarioId = trim((string) ($filters['calendario_id'] ?? ''));
        $planillaId = trim((string) ($filters['planilla_id'] ?? ''));
        $planId = trim((string) ($filters['plan_id'] ?? ''));

        $where = [];
        $params = [];
        if ($calendarioId !== '') {
            $where[] = 'comision.calendario = :calendario_id';
            $where[] = 'toma.planilla_docente IS NULL';
            $where[] = "toma.estado = 'Aprobada'";
            $where[] = "toma.estado_contralor = 'Pasar'";
            $params['calendario_id'] = $calendarioId;
        } elseif ($planillaId !== '') {
            $where[] = 'toma.planilla_docente = :planilla_id';
            $params['planilla_id'] = $planillaId;
        } else {
            throw new \InvalidArgumentException('Indicá un calendario o una planilla docente.');
        }

        if ($planId !== '') {
            $where[] = 'comision_planificacion.plan = :plan_id';
            $params['plan_id'] = $planId;
        }

        return [$where, $params];
    }

    /**
     * @param array<string, mixed> $row
     * @return list<string>
     */
    private function contralorCopyCells(array $row): array
    {
        [$cuil1, $cuil2] = $this->cuilParts($row);
        $fechaToma = $this->dateParts($row['fecha_toma'] ?? null);
        $fechaFin = $this->dateParts($row['calendario_fin'] ?? null);
        $horas = $row['curso_horas_catedra'] ?? null;
        if ($horas === null || $horas === '') {
            $horas = $row['disposicion_horas_catedra'] ?? '';
        }

        $codigo = trim((string) ($row['asignatura_codigo'] ?? ''));
        if ($codigo !== '') {
            $parts = preg_split('/[ ,]/', $codigo, 2) ?: [];
            $codigo = (string) ($parts[0] ?? '');
        }

        $apellidos = mb_strtoupper(trim((string) ($row['docente_apellidos'] ?? '')), 'UTF-8');
        $nombres = mb_convert_case(
            mb_strtolower(trim((string) ($row['docente_nombres'] ?? '')), 'UTF-8'),
            MB_CASE_TITLE,
            'UTF-8',
        );

        return [
            'S/N',
            $cuil1,
            (string) ($row['docente_documento'] ?? ''),
            $cuil2,
            '',
            persona_fecha_nacimiento([
                'dia_nacimiento' => $row['docente_dia_nacimiento'] ?? null,
                'mes_nacimiento' => $row['docente_mes_nacimiento'] ?? null,
                'anio_nacimiento' => $row['docente_anio_nacimiento'] ?? null,
            ]),
            $apellidos . ', ' . $nombres,
            'P',
            $this->orientacionAcronym((string) ($row['plan_orientacion'] ?? '')),
            $codigo,
            (string) $horas,
            'PF',
            (string) ($row['planificacion_anio'] ?? ''),
            (string) ($row['planificacion_semestre'] ?? ''),
            mb_substr(trim((string) ($row['turno'] ?? '')), 0, 1, 'UTF-8'),
            (string) ($row['tipo_movimiento'] ?? ''),
            $fechaToma['d'],
            $fechaToma['m'],
            $fechaToma['y'],
            $fechaFin['d'],
            $fechaFin['m'],
            $fechaFin['y'],
        ];
    }

    /**
     * @param array<string, mixed> $row
     * @return array{0: string, 1: string}
     */
    private function cuilParts(array $row): array
    {
        $cuil1 = trim((string) ($row['docente_cuil1'] ?? ''));
        $cuil2 = trim((string) ($row['docente_cuil2'] ?? ''));
        $cuilDigits = preg_replace('/\D+/', '', (string) ($row['docente_cuil'] ?? '')) ?? '';
        if ($cuil1 === '' && strlen($cuilDigits) >= 3) {
            $cuil1 = substr($cuilDigits, 0, 2);
        }
        if ($cuil2 === '' && $cuilDigits !== '') {
            $cuil2 = substr($cuilDigits, -1);
        }
        if ($cuil1 !== '') {
            $cuil1 = str_pad($cuil1, 2, '0', STR_PAD_LEFT);
        }

        return [$cuil1, $cuil2];
    }

    private function orientacionAcronym(string $orientacion): string
    {
        $words = preg_split('/\s+/u', trim($orientacion), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $acronym = '';
        foreach ($words as $word) {
            $acronym .= mb_substr($word, 0, 1, 'UTF-8');
        }

        return mb_strtoupper($acronym, 'UTF-8');
    }

    /**
     * @return array{d: string, m: string, y: string}
     */
    private function dateParts(mixed $value): array
    {
        $raw = trim((string) ($value ?? ''));
        if ($raw === '') {
            return ['d' => '', 'm' => '', 'y' => ''];
        }
        $date = date_create($raw);
        if (!$date instanceof \DateTimeInterface) {
            return ['d' => '', 'm' => '', 'y' => ''];
        }

        return [
            'd' => $date->format('d'),
            'm' => $date->format('m'),
            'y' => $date->format('y'),
        ];
    }
}
