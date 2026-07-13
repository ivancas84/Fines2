<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class SedeRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(string $sort = 'numero', string $order = 'asc'): array
    {
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->query("
            SELECT sede.id,
                   sede.numero,
                   sede.nombre,
                   COALESCE(centro_educativo.nombre, '') AS cens,
                   sede.fecha_traspaso,
                   TRIM(CONCAT(
                       COALESCE(domicilio.calle, ''),
                       IF(NULLIF(TRIM(domicilio.numero), '') IS NOT NULL, CONCAT(' N°', domicilio.numero), ''),
                       IF(NULLIF(TRIM(domicilio.entre), '') IS NOT NULL, CONCAT(' e/', domicilio.entre), ''),
                       IF(NULLIF(TRIM(domicilio.barrio), '') IS NOT NULL, CONCAT(' ', domicilio.barrio), ''),
                       IF(NULLIF(TRIM(domicilio.localidad), '') IS NOT NULL, CONCAT(' ', domicilio.localidad), '')
                   )) AS domicilio_label,
                   COALESCE(referentes.referentes_label, 'Sin Referentes') AS referentes_label
            FROM sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN centro_educativo ON centro_educativo.id = sede.centro_educativo
            LEFT JOIN (
                SELECT designacion.sede,
                       GROUP_CONCAT(
                           CONCAT_WS(
                               ' ',
                               NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), ''),
                               NULLIF(TRIM(persona.telefono), '')
                           )
                           ORDER BY persona.apellidos, persona.nombres
                           SEPARATOR ', '
                       ) AS referentes_label
                FROM designacion
                INNER JOIN persona ON persona.id = designacion.persona
                WHERE designacion.cargo = '1' AND designacion.hasta IS NULL
                GROUP BY designacion.sede
            ) referentes ON referentes.sede = sede.id
            ORDER BY {$orderBy}
        ");

        return $stmt->fetchAll();
    }

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        return match ($sort) {
            'nombre' => "sede.nombre {$direction}, sede.numero ASC",
            'cens' => "COALESCE(centro_educativo.nombre, '') {$direction}, sede.numero ASC",
            'fecha_traspaso' => "sede.fecha_traspaso {$direction}, sede.numero ASC",
            default => "sede.numero {$direction}, sede.nombre ASC",
        };
    }
}
