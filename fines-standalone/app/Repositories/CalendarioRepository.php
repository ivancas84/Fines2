<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class CalendarioRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(string $sort = 'anio', string $order = 'desc'): array
    {
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->query("
            SELECT calendario.id,
                   calendario.inicio,
                   calendario.fin,
                   calendario.anio,
                   calendario.semestre,
                   calendario.insertado,
                   calendario.descripcion,
                   TRIM(CONCAT_WS(
                       ' ',
                       TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))),
                       NULLIF(TRIM(calendario.descripcion), '')
                   )) AS label,
                   COALESCE(comisiones.total, 0) AS comisiones_count
            FROM calendario
            LEFT JOIN (
                SELECT calendario, COUNT(*) AS total
                FROM comision
                GROUP BY calendario
            ) comisiones ON comisiones.calendario = calendario.id
            ORDER BY {$orderBy}
        ");

        return $stmt->fetchAll();
    }

    public function byId(string $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT calendario.id,
                   calendario.inicio,
                   calendario.fin,
                   calendario.anio,
                   calendario.semestre,
                   calendario.insertado,
                   calendario.descripcion,
                   TRIM(CONCAT_WS(
                       ' ',
                       TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))),
                       NULLIF(TRIM(calendario.descripcion), '')
                   )) AS label,
                   COALESCE(comisiones.total, 0) AS comisiones_count
            FROM calendario
            LEFT JOIN (
                SELECT calendario, COUNT(*) AS total
                FROM comision
                GROUP BY calendario
            ) comisiones ON comisiones.calendario = calendario.id
            WHERE calendario.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{
     *   anio: int,
     *   semestre: int,
     *   inicio: ?string,
     *   fin: ?string,
     *   descripcion: ?string
     * } $data
     */
    public function create(array $data): string
    {
        $id = uniqid();

        $stmt = $this->pdo->prepare("
            INSERT INTO calendario (id, anio, semestre, inicio, fin, descripcion)
            VALUES (:id, :anio, :semestre, :inicio, :fin, :descripcion)
        ");
        $stmt->execute([
            'id' => $id,
            'anio' => $data['anio'],
            'semestre' => $data['semestre'],
            'inicio' => $data['inicio'],
            'fin' => $data['fin'],
            'descripcion' => $data['descripcion'],
        ]);

        return $id;
    }

    /**
     * @param array{
     *   anio: int,
     *   semestre: int,
     *   inicio: ?string,
     *   fin: ?string,
     *   descripcion: ?string
     * } $data
     */
    public function update(string $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE calendario
            SET anio = :anio,
                semestre = :semestre,
                inicio = :inicio,
                fin = :fin,
                descripcion = :descripcion
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'anio' => $data['anio'],
            'semestre' => $data['semestre'],
            'inicio' => $data['inicio'],
            'fin' => $data['fin'],
            'descripcion' => $data['descripcion'],
        ]);
    }

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return match ($sort) {
            'semestre' => "calendario.semestre {$direction}, calendario.anio DESC",
            'inicio' => "calendario.inicio {$direction}, calendario.anio DESC, calendario.semestre DESC",
            'fin' => "calendario.fin {$direction}, calendario.anio DESC, calendario.semestre DESC",
            'descripcion' => "calendario.descripcion {$direction}, calendario.anio DESC, calendario.semestre DESC",
            'comisiones' => "comisiones_count {$direction}, calendario.anio DESC, calendario.semestre DESC",
            default => "calendario.anio {$direction}, calendario.semestre DESC",
        };
    }
}
