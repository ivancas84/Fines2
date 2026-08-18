<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class TomaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byDocente(string $personaId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.fecha_toma,
                   toma.estado,
                   toma.tipo_movimiento,
                   toma.estado_contralor,
                   toma.estado_planilla,
                   toma.reclamo,
                   toma.observaciones,
                   toma.curso AS curso_id,
                   curso.descripcion_horario,
                   curso.codigo AS curso_codigo,
                   asignatura.nombre AS asignatura_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   comision.id AS comision_id,
                   comision.pfid,
                   COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   calendario.inicio AS calendario_inicio,
                   calendario.fin AS calendario_fin,
                   planilla_docente.numero AS planilla_numero
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN comision ON comision.id = curso.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planilla_docente ON planilla_docente.id = toma.planilla_docente
            WHERE toma.docente = :persona_id
            ORDER BY toma.fecha_toma DESC,
                     toma.alta DESC,
                     toma.id DESC
        ");
        $stmt->execute(['persona_id' => $personaId]);

        return $stmt->fetchAll();
    }

    public function estados(): array
    {
        return $this->distinctColumn('estado');
    }

    public function tiposMovimiento(): array
    {
        return $this->distinctColumn('tipo_movimiento');
    }

    public function estadosContralor(): array
    {
        return $this->distinctColumn('estado_contralor');
    }

    public function updateForDocente(string $personaId, array $rows): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE toma
            SET fecha_toma = :fecha_toma,
                estado = :estado,
                tipo_movimiento = :tipo_movimiento,
                estado_contralor = :estado_contralor,
                observaciones = :observaciones
            WHERE id = :id AND docente = :docente
        ");

        foreach ($rows as $row) {
            $id = (string) ($row['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $stmt->execute([
                'id' => $id,
                'docente' => $personaId,
                'fecha_toma' => $this->nullableDate($row['fecha_toma'] ?? null),
                'estado' => $this->nullableText($row['estado'] ?? null),
                'tipo_movimiento' => $this->nullableText($row['tipo_movimiento'] ?? null),
                'estado_contralor' => $this->nullableText($row['estado_contralor'] ?? null),
                'observaciones' => $this->nullableText($row['observaciones'] ?? null),
            ]);
        }
    }

    public function byComision(string $comisionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.fecha_toma,
                   toma.estado,
                   toma.tipo_movimiento,
                   toma.estado_contralor,
                   toma.estado_planilla,
                   toma.observaciones,
                   toma.curso AS curso_id,
                   toma.docente AS docente_id,
                   curso.descripcion_horario,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   docente.nombres AS docente_nombres,
                   docente.apellidos AS docente_apellidos,
                   docente.numero_documento AS docente_documento,
                   docente.telefono AS docente_telefono,
                   docente.email AS docente_email,
                   docente.email_abc AS docente_email_abc,
                   planilla_docente.numero AS planilla_numero
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN persona docente ON docente.id = toma.docente
            LEFT JOIN planilla_docente ON planilla_docente.id = toma.planilla_docente
            WHERE curso.comision = :comision_id
            ORDER BY toma.fecha_toma DESC, toma.alta DESC, toma.id DESC
        ");
        $stmt->execute(['comision_id' => $comisionId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Datos completos de una toma para generar PDF / email en constancias.
     *
     * @return array{
     *   toma_id: string,
     *   comision_id: string,
     *   curso_id: string,
     *   docente: array<string, mixed>,
     *   cargo: array<string, mixed>
     * }|null
     */
    public function payloadForGenerar(string $comisionId, string $tomaId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id AS toma_id,
                   toma.fecha_toma,
                   toma.curso AS curso_id,
                   toma.docente AS docente_id,
                   comision.id AS comision_id,
                   comision.pfid,
                   comision.calendario AS calendario_id,
                   curso.descripcion_horario,
                   curso.horas_catedra AS curso_horas_catedra,
                   sede.nombre AS sede_nombre,
                   sede.numero AS sede_numero,
                   TRIM(CONCAT(
                       COALESCE(domicilio.calle, ''),
                       IF(NULLIF(TRIM(domicilio.numero), '') IS NOT NULL, CONCAT(' N°', domicilio.numero), ''),
                       IF(NULLIF(TRIM(domicilio.entre), '') IS NOT NULL, CONCAT(' e/', domicilio.entre), ''),
                       IF(NULLIF(TRIM(domicilio.barrio), '') IS NOT NULL, CONCAT(' ', domicilio.barrio), ''),
                       IF(NULLIF(TRIM(domicilio.localidad), '') IS NOT NULL, CONCAT(' ', domicilio.localidad), '')
                   )) AS domicilio_sede,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   disposicion.horas_catedra AS disposicion_horas_catedra,
                   planificacion.anio AS planificacion_anio,
                   planificacion.semestre AS planificacion_semestre,
                   plan.resolucion AS plan_resolucion,
                   calendario.inicio AS calendario_inicio,
                   calendario.fin AS calendario_fin,
                   docente.nombres AS docente_nombres,
                   docente.apellidos AS docente_apellidos,
                   docente.numero_documento AS docente_documento,
                   docente.cuil AS docente_cuil,
                   docente.cuil1 AS docente_cuil1,
                   docente.cuil2 AS docente_cuil2,
                   docente.fecha_nacimiento AS docente_fecha_nacimiento,
                   docente.dia_nacimiento AS docente_dia_nacimiento,
                   docente.mes_nacimiento AS docente_mes_nacimiento,
                   docente.anio_nacimiento AS docente_anio_nacimiento,
                   docente.telefono AS docente_telefono,
                   docente.email AS docente_email,
                   docente.email_abc AS docente_email_abc,
                   docente.descripcion_domicilio AS docente_domicilio
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            INNER JOIN comision ON comision.id = curso.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = COALESCE(disposicion.planificacion, comision.planificacion)
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN persona docente ON docente.id = toma.docente
            WHERE toma.id = :toma_id
              AND comision.id = :comision_id
            LIMIT 1
        ");
        $stmt->execute([
            'toma_id' => $tomaId,
            'comision_id' => $comisionId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $dni = preg_replace('/\D+/', '', (string) ($row['docente_documento'] ?? '')) ?? '';
        $cuil = trim((string) ($row['docente_cuil'] ?? ''));
        if ($cuil === '') {
            $cuil1 = trim((string) ($row['docente_cuil1'] ?? ''));
            $cuil2 = trim((string) ($row['docente_cuil2'] ?? ''));
            if ($cuil1 !== '' && $dni !== '' && $cuil2 !== '') {
                $cuil = str_pad($cuil1, 2, '0', STR_PAD_LEFT)
                    . str_pad($dni, 8, '0', STR_PAD_LEFT)
                    . substr($cuil2, -1);
            }
        }

        $fechaNac = $this->formatDisplayDate($row['docente_fecha_nacimiento'] ?? null);
        if ($fechaNac === '') {
            $dia = (int) ($row['docente_dia_nacimiento'] ?? 0);
            $mes = (int) ($row['docente_mes_nacimiento'] ?? 0);
            $anio = (int) ($row['docente_anio_nacimiento'] ?? 0);
            if ($dia > 0 && $mes > 0 && $anio > 0 && checkdate($mes, $dia, $anio)) {
                $fechaNac = sprintf('%02d/%02d/%04d', $dia, $mes, $anio);
            }
        }

        $fechaToma = $this->formatDisplayDate($row['fecha_toma'] ?? null);
        if ($fechaToma === '') {
            $fechaToma = $this->formatDisplayDate($row['calendario_inicio'] ?? null);
        }
        $fechaFin = $this->formatDisplayDate($row['calendario_fin'] ?? null);

        $asignatura = trim((string) ($row['asignatura_nombre'] ?? ''));
        $tramo = '';
        if (($row['planificacion_anio'] ?? '') !== '' || ($row['planificacion_semestre'] ?? '') !== '') {
            $tramo = trim(($row['planificacion_anio'] ?? '') . '°' . ($row['planificacion_semestre'] ?? '') . 'C');
        }

        $horas = $row['curso_horas_catedra'] ?? $row['disposicion_horas_catedra'] ?? '';
        $sede = trim((string) ($row['sede_nombre'] ?? ''));
        if ($sede === '') {
            $sede = trim((string) ($row['sede_numero'] ?? ''));
        }

        return [
            'toma_id' => (string) $row['toma_id'],
            'comision_id' => (string) $row['comision_id'],
            'curso_id' => (string) $row['curso_id'],
            'docente' => [
                'nombres' => (string) ($row['docente_nombres'] ?? ''),
                'apellidos' => (string) ($row['docente_apellidos'] ?? ''),
                'numero_documento' => $dni,
                'cuil' => $cuil,
                'fecha_nacimiento' => $fechaNac,
                'email' => (string) ($row['docente_email'] ?? ''),
                'email_abc' => (string) ($row['docente_email_abc'] ?? ''),
                'descripcion_domicilio' => (string) ($row['docente_domicilio'] ?? ''),
                'telefono' => (string) ($row['docente_telefono'] ?? ''),
            ],
            'cargo' => [
                'sede' => $sede,
                'domicilio_sede' => trim((string) ($row['domicilio_sede'] ?? '')),
                'pfid' => (string) ($row['pfid'] ?? ''),
                'horario' => (string) ($row['descripcion_horario'] ?? ''),
                'fecha_toma' => $fechaToma,
                'fecha_fin' => $fechaFin,
                'asignatura' => $asignatura,
                'horas_catedra' => (string) $horas,
                'tramo' => $tramo,
                'resolucion' => (string) ($row['plan_resolucion'] ?? ''),
            ],
        ];
    }

    /**
     * Parámetros GET para abrir el formulario de toma en constancias-standalone.
     *
     * @param array{
     *   docente?: array<string, mixed>,
     *   cargo?: array<string, mixed>
     * } $payload
     * @return array<string, string>
     */
    public function toConstanciaQuery(array $payload): array
    {
        $docente = is_array($payload['docente'] ?? null) ? $payload['docente'] : [];
        $cargo = is_array($payload['cargo'] ?? null) ? $payload['cargo'] : [];
        $emails = array_values(array_filter([
            trim((string) ($docente['email_abc'] ?? '')),
            trim((string) ($docente['email'] ?? '')),
        ]));

        return [
            'nombres' => (string) ($docente['nombres'] ?? ''),
            'apellidos' => (string) ($docente['apellidos'] ?? ''),
            'numero_documento' => (string) ($docente['numero_documento'] ?? ''),
            'cuil' => (string) ($docente['cuil'] ?? ''),
            'fecha_nacimiento' => (string) ($docente['fecha_nacimiento'] ?? ''),
            'telefono' => (string) ($docente['telefono'] ?? ''),
            'descripcion_domicilio' => (string) ($docente['descripcion_domicilio'] ?? ''),
            'email' => (string) ($docente['email'] ?? ''),
            'email_abc' => (string) ($docente['email_abc'] ?? ''),
            'emails' => implode(', ', $emails),
            'sede' => (string) ($cargo['sede'] ?? ''),
            'domicilio_sede' => (string) ($cargo['domicilio_sede'] ?? ''),
            'pfid' => (string) ($cargo['pfid'] ?? ''),
            'horario' => (string) ($cargo['horario'] ?? ''),
            'fecha_toma' => (string) ($cargo['fecha_toma'] ?? ''),
            'fecha_fin' => (string) ($cargo['fecha_fin'] ?? ''),
            'asignatura' => (string) ($cargo['asignatura'] ?? ''),
            'horas_catedra' => (string) ($cargo['horas_catedra'] ?? ''),
            'tramo' => (string) ($cargo['tramo'] ?? ''),
            'resolucion' => (string) ($cargo['resolucion'] ?? ''),
            'contenido_html' => $this->contenidoHtmlFromPayload($docente, $cargo),
            'incluir_firmas' => '1',
            'enviar_email' => $emails === [] ? '' : '1',
        ];
    }

    /**
     * @param array<string, mixed> $docente
     * @param array<string, mixed> $cargo
     */
    private function contenidoHtmlFromPayload(array $docente, array $cargo): string
    {
        $escape = static fn (mixed $value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
        $nombre = trim(
            (string) ($docente['apellidos'] ?? '') . ', ' . (string) ($docente['nombres'] ?? ''),
            " \t\n\r\0\x0B,",
        );
        $emails = trim(implode(' / ', array_filter([
            trim((string) ($docente['email_abc'] ?? '')),
            trim((string) ($docente['email'] ?? '')),
        ])));

        return '<table border="1" cellpadding="5">'
            . '<tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Docente</b></th></tr>'
            . '<tr><td><b>Nombre</b></td><td colspan="3">' . $escape($nombre) . '</td></tr>'
            . '<tr><td><b>CUIL</b></td><td>' . $escape($docente['cuil'] ?? '') . '</td>'
            . '<td><b>Fecha de Nacimiento</b></td><td>' . $escape($docente['fecha_nacimiento'] ?? '') . '</td></tr>'
            . '<tr><td><b>Email</b></td><td colspan="3">' . $escape($emails) . '</td></tr>'
            . '<tr><td><b>Domicilio</b></td><td colspan="3">' . $escape($docente['descripcion_domicilio'] ?? '') . '</td></tr>'
            . '<tr><td><b>Teléfono</b></td><td colspan="3">' . $escape($docente['telefono'] ?? '') . '</td></tr>'
            . '</table><br><table border="1" cellpadding="5">'
            . '<tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Cargo</b></th></tr>'
            . '<tr><td><b>Sede</b></td><td>' . $escape($cargo['sede'] ?? '') . '</td>'
            . '<td><b>Comisión</b></td><td>' . $escape($cargo['pfid'] ?? '') . '</td></tr>'
            . '<tr><td><b>Domicilio</b></td><td colspan="3">' . $escape($cargo['domicilio_sede'] ?? '') . '</td></tr>'
            . '<tr><td><b>Horario</b></td><td colspan="3">' . $escape($cargo['horario'] ?? '') . '</td></tr>'
            . '<tr><td><b>Fecha Toma</b></td><td>' . $escape($cargo['fecha_toma'] ?? '') . '</td>'
            . '<td><b>Fecha Fin</b></td><td>' . $escape($cargo['fecha_fin'] ?? '') . '</td></tr>'
            . '<tr><td><b>Asignatura</b></td><td>' . $escape($cargo['asignatura'] ?? '') . '</td>'
            . '<td><b>Hs Cát</b></td><td>' . $escape($cargo['horas_catedra'] ?? '') . '</td></tr>'
            . '<tr><td><b>Tramo</b></td><td>' . $escape($cargo['tramo'] ?? '') . '</td>'
            . '<td><b>Resolución</b></td><td>' . $escape($cargo['resolucion'] ?? '') . '</td></tr>'
            . '</table>';
    }

    private function formatDisplayDate(mixed $value): string
    {
        $raw = trim((string) ($value ?? ''));
        if ($raw === '') {
            return '';
        }
        $date = date_create($raw);

        return $date instanceof \DateTimeInterface ? $date->format('d/m/Y') : $raw;
    }

    /**
     * @param list<array{
     *   id: string,
     *   fecha_toma?: mixed,
     *   curso?: mixed,
     *   dni_docente?: mixed,
     *   estado?: mixed,
     *   tipo_movimiento?: mixed,
     *   estado_contralor?: mixed
     * }> $rows
     */
    public function updateForComision(string $comisionId, array $rows): void
    {
        $this->pdo->beginTransaction();
        try {
            foreach ($rows as $row) {
                $id = trim((string) ($row['id'] ?? ''));
                if ($id === '') {
                    continue;
                }

                $current = $this->findInComision($comisionId, $id);
                if ($current === null) {
                    throw new \RuntimeException("No se encontró la toma {$id} en la comisión.");
                }

                $docenteId = (string) ($current['docente'] ?? '');
                $dniNuevo = preg_replace('/\D+/', '', (string) ($row['dni_docente'] ?? '')) ?? '';
                $dniActual = preg_replace('/\D+/', '', (string) ($current['docente_documento'] ?? '')) ?? '';
                if ($dniNuevo !== '' && $dniNuevo !== $dniActual) {
                    $docenteId = $this->personaIdByDni($dniNuevo);
                }

                $tipoMovimiento = $this->nullableText($row['tipo_movimiento'] ?? null)
                    ?? $this->nullableText($current['tipo_movimiento'] ?? null)
                    ?? 'Alta';

                $stmt = $this->pdo->prepare("
                    UPDATE toma
                    SET fecha_toma = :fecha_toma,
                        curso = :curso,
                        docente = :docente,
                        estado = :estado,
                        tipo_movimiento = :tipo_movimiento,
                        estado_contralor = :estado_contralor
                    WHERE id = :id
                ");
                $stmt->execute([
                    'id' => $id,
                    'fecha_toma' => $this->nullableDate($row['fecha_toma'] ?? null),
                    'curso' => $this->cursoInComision($comisionId, (string) ($row['curso'] ?? $current['curso'])),
                    'docente' => $docenteId !== '' ? $docenteId : null,
                    'estado' => $this->nullableText($row['estado'] ?? null),
                    'tipo_movimiento' => $tipoMovimiento,
                    'estado_contralor' => $this->nullableText($row['estado_contralor'] ?? null),
                ]);
            }

            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    public function addForComision(string $comisionId, array $data): string
    {
        $cursoId = $this->cursoInComision($comisionId, (string) ($data['curso'] ?? ''));
        $dni = preg_replace('/\D+/', '', (string) ($data['dni_docente'] ?? '')) ?? '';
        if ($dni === '') {
            throw new \InvalidArgumentException('Indicá el DNI del docente.');
        }
        $docenteId = $this->personaIdByDni($dni);
        $tipoMovimiento = $this->nullableText($data['tipo_movimiento'] ?? null);
        if ($tipoMovimiento === null) {
            throw new \InvalidArgumentException('Indicá el tipo de movimiento.');
        }

        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO toma (id, fecha_toma, curso, docente, estado, tipo_movimiento, estado_contralor)
            VALUES (:id, :fecha_toma, :curso, :docente, :estado, :tipo_movimiento, :estado_contralor)
        ");
        $stmt->execute([
            'id' => $id,
            'fecha_toma' => $this->nullableDate($data['fecha_toma'] ?? null),
            'curso' => $cursoId,
            'docente' => $docenteId,
            'estado' => $this->nullableText($data['estado'] ?? null),
            'tipo_movimiento' => $tipoMovimiento,
            'estado_contralor' => $this->nullableText($data['estado_contralor'] ?? null),
        ]);

        return $id;
    }

    public function deleteInComision(string $comisionId, string $tomaId): void
    {
        $current = $this->findInComision($comisionId, $tomaId);
        if ($current === null) {
            throw new \RuntimeException('No se encontró la toma para eliminar.');
        }

        $stmt = $this->pdo->prepare('DELETE FROM toma WHERE id = :id');
        $stmt->execute(['id' => $tomaId]);
    }

    /** @return array<string, mixed>|null */
    private function findInComision(string $comisionId, string $tomaId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.curso,
                   toma.docente,
                   toma.tipo_movimiento,
                   persona.numero_documento AS docente_documento
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN persona ON persona.id = toma.docente
            WHERE toma.id = :toma_id
              AND curso.comision = :comision_id
            LIMIT 1
        ");
        $stmt->execute([
            'toma_id' => $tomaId,
            'comision_id' => $comisionId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function cursoInComision(string $comisionId, string $cursoId): string
    {
        $cursoId = trim($cursoId);
        if ($cursoId === '') {
            throw new \InvalidArgumentException('Seleccioná un curso.');
        }

        $stmt = $this->pdo->prepare('
            SELECT id FROM curso
            WHERE id = :curso_id AND comision = :comision_id
            LIMIT 1
        ');
        $stmt->execute([
            'curso_id' => $cursoId,
            'comision_id' => $comisionId,
        ]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            throw new \RuntimeException('El curso no pertenece a la comisión.');
        }

        return (string) $id;
    }

    private function personaIdByDni(string $dni): string
    {
        $stmt = $this->pdo->prepare('SELECT id FROM persona WHERE numero_documento = :dni LIMIT 1');
        $stmt->execute(['dni' => $dni]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            throw new \RuntimeException('No se encontró el docente con el DNI proporcionado.');
        }

        return (string) $id;
    }

    public function updateEstadoPlanilla(string $tomaId, string $estado = 'entregada'): void
    {
        $tomaId = trim($tomaId);
        $estado = trim($estado);
        if ($tomaId === '') {
            throw new \InvalidArgumentException('Falta el id de la toma.');
        }
        if ($estado === '') {
            throw new \InvalidArgumentException('Falta el estado de planilla.');
        }

        $stmt = $this->pdo->prepare('
            UPDATE toma
            SET estado_planilla = :estado
            WHERE id = :id
        ');
        $stmt->execute([
            'id' => $tomaId,
            'estado' => $estado,
        ]);

        if ($stmt->rowCount() === 0) {
            $exists = $this->pdo->prepare('SELECT id FROM toma WHERE id = :id LIMIT 1');
            $exists->execute(['id' => $tomaId]);
            if ($exists->fetchColumn() === false) {
                throw new \RuntimeException('No se encontró la toma indicada.');
            }
            // rowCount 0 puede ser "mismo valor"; no es error.
        }
    }

    private function distinctColumn(string $column): array
    {
        $allowed = ['estado', 'tipo_movimiento', 'estado_contralor'];
        if (!in_array($column, $allowed, true)) {
            throw new \InvalidArgumentException('Columna de toma no permitida.');
        }

        return $this->pdo
            ->query("SELECT DISTINCT {$column} FROM toma WHERE {$column} IS NOT NULL AND {$column} != '' ORDER BY {$column} ASC")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    private function nullableDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches) === 1) {
            $day = (int) $matches[1];
            $month = (int) $matches[2];
            $year = (int) $matches[3];
            if (checkdate($month, $day, $year)) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }

        throw new \RuntimeException('La fecha de toma debe tener formato dd/mm/aaaa.');
    }
}
