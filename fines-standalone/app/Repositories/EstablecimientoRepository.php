<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class EstablecimientoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byUser(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*
            FROM fines_app_users u
            LEFT JOIN fines_app_establecimientos e ON e.id = u.establecimiento_id
            WHERE u.id = :user_id
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();

        if (!$row || $row['id'] === null) {
            return null;
        }

        return $row;
    }

    public function saveForUser(int $userId, array $data): int
    {
        $current = $this->byUser($userId);
        if ($current === null) {
            $stmt = $this->pdo->prepare("
                INSERT INTO fines_app_establecimientos (
                    nombre, cue, direccion, logo_path, firma_director_path, sello_oval_path
                ) VALUES (
                    :nombre, :cue, :direccion, :logo_path, :firma_director_path, :sello_oval_path
                )
            ");
            $stmt->execute([
                'nombre' => $data['nombre'],
                'cue' => $data['cue'] ?: null,
                'direccion' => $data['direccion'] ?: null,
                'logo_path' => $data['logo_path'] ?? null,
                'firma_director_path' => $data['firma_director_path'] ?? null,
                'sello_oval_path' => $data['sello_oval_path'] ?? null,
            ]);
            $id = (int) $this->pdo->lastInsertId();
            $this->pdo->prepare('UPDATE fines_app_users SET establecimiento_id = :establecimiento_id WHERE id = :user_id')
                ->execute(['establecimiento_id' => $id, 'user_id' => $userId]);

            return $id;
        }

        $stmt = $this->pdo->prepare("
            UPDATE fines_app_establecimientos
            SET nombre = :nombre,
                cue = :cue,
                direccion = :direccion,
                logo_path = :logo_path,
                firma_director_path = :firma_director_path,
                sello_oval_path = :sello_oval_path
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => (int) $current['id'],
            'nombre' => $data['nombre'],
            'cue' => $data['cue'] ?: null,
            'direccion' => $data['direccion'] ?: null,
            'logo_path' => $data['logo_path'] ?? $current['logo_path'],
            'firma_director_path' => $data['firma_director_path'] ?? $current['firma_director_path'],
            'sello_oval_path' => $data['sello_oval_path'] ?? $current['sello_oval_path'],
        ]);

        return (int) $current['id'];
    }
}
