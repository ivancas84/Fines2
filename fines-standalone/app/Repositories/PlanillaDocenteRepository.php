<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class PlanillaDocenteRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(string $sort = 'insertado', string $order = 'desc'): array
    {
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->query("
            SELECT planilla_docente.id,
                   planilla_docente.numero,
                   planilla_docente.fecha_contralor,
                   planilla_docente.fecha_consejo,
                   planilla_docente.observaciones,
                   planilla_docente.insertado,
                   COALESCE(tomas.total, 0) AS tomas_count
            FROM planilla_docente
            LEFT JOIN (
                SELECT planilla_docente, COUNT(*) AS total
                FROM toma
                GROUP BY planilla_docente
            ) tomas ON tomas.planilla_docente = planilla_docente.id
            ORDER BY {$orderBy}
        ");

        $rows = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $rows[] = $this->withLabel($row);
        }

        return $rows;
    }

    /** @return array<string, mixed>|null */
    public function byId(string $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT planilla_docente.id,
                   planilla_docente.numero,
                   planilla_docente.fecha_contralor,
                   planilla_docente.fecha_consejo,
                   planilla_docente.observaciones,
                   planilla_docente.insertado,
                   COALESCE(tomas.total, 0) AS tomas_count
            FROM planilla_docente
            LEFT JOIN (
                SELECT planilla_docente, COUNT(*) AS total
                FROM toma
                GROUP BY planilla_docente
            ) tomas ON tomas.planilla_docente = planilla_docente.id
            WHERE planilla_docente.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $this->withLabel($row);
    }

    /**
     * @param array{
     *   numero: string,
     *   fecha_contralor: ?string,
     *   fecha_consejo: ?string,
     *   observaciones: ?string
     * } $data
     */
    public function create(array $data): string
    {
        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO planilla_docente (
                id, numero, fecha_contralor, fecha_consejo, observaciones
            ) VALUES (
                :id, :numero, :fecha_contralor, :fecha_consejo, :observaciones
            )
        ");
        $stmt->execute([
            'id' => $id,
            'numero' => $data['numero'],
            'fecha_contralor' => $data['fecha_contralor'],
            'fecha_consejo' => $data['fecha_consejo'],
            'observaciones' => $data['observaciones'],
        ]);

        return $id;
    }

    /**
     * @param array{
     *   numero: string,
     *   fecha_contralor: ?string,
     *   fecha_consejo: ?string,
     *   observaciones: ?string
     * } $data
     */
    public function update(string $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE planilla_docente
            SET numero = :numero,
                fecha_contralor = :fecha_contralor,
                fecha_consejo = :fecha_consejo,
                observaciones = :observaciones
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'numero' => $data['numero'],
            'fecha_contralor' => $data['fecha_contralor'],
            'fecha_consejo' => $data['fecha_consejo'],
            'observaciones' => $data['observaciones'],
        ]);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function withLabel(array $row): array
    {
        $numero = trim((string) ($row['numero'] ?? ''));
        $fecha = $this->formatDisplayDate($row['fecha_contralor'] ?? null);
        $label = $numero;
        if ($fecha !== '') {
            $label = $numero !== '' ? $numero . ' (' . $fecha . ')' : $fecha;
        }
        $row['label'] = $label !== '' ? $label : (string) ($row['id'] ?? '');

        return $row;
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

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return match ($sort) {
            'numero' => "planilla_docente.numero {$direction}, planilla_docente.insertado DESC",
            'fecha_contralor' => "planilla_docente.fecha_contralor {$direction}, planilla_docente.insertado DESC",
            'fecha_consejo' => "planilla_docente.fecha_consejo {$direction}, planilla_docente.insertado DESC",
            'tomas' => "tomas_count {$direction}, planilla_docente.insertado DESC",
            default => "planilla_docente.insertado {$direction}, planilla_docente.numero ASC",
        };
    }
}
