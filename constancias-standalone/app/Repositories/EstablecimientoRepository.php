<?php

declare(strict_types=1);

namespace ConstanciasApp\Repositories;

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

    public function byId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM fines_app_establecimientos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function firstActive(): ?array
    {
        $stmt = $this->pdo->query('
            SELECT *
            FROM fines_app_establecimientos
            WHERE activo = 1
            ORDER BY id ASC
            LIMIT 1
        ');
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function saveForUser(int $userId, array $data): int
    {
        $current = $this->byUser($userId);
        if ($current === null) {
            $stmt = $this->pdo->prepare("
                INSERT INTO fines_app_establecimientos (
                    nombre, localidad, modalidad_principal, orientacion_principal, resolucion_principal,
                    firma_director_path, sello_oval_path
                ) VALUES (
                    :nombre, :localidad, :modalidad_principal, :orientacion_principal, :resolucion_principal,
                    :firma_director_path, :sello_oval_path
                )
            ");
            $stmt->execute([
                'nombre' => $data['nombre'],
                'localidad' => $data['localidad'] ?: 'La Plata',
                'modalidad_principal' => $data['modalidad_principal'] ?: 'Programa Fines 2 Trayecto Secundario',
                'orientacion_principal' => $data['orientacion_principal'] ?: 'Ciencias Sociales',
                'resolucion_principal' => $data['resolucion_principal'] ?: '2993/22',
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
                localidad = :localidad,
                modalidad_principal = :modalidad_principal,
                orientacion_principal = :orientacion_principal,
                resolucion_principal = :resolucion_principal,
                firma_director_path = :firma_director_path,
                sello_oval_path = :sello_oval_path
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => (int) $current['id'],
            'nombre' => $data['nombre'],
            'localidad' => $data['localidad'] ?: 'La Plata',
            'modalidad_principal' => $data['modalidad_principal'] ?: 'Programa Fines 2 Trayecto Secundario',
            'orientacion_principal' => $data['orientacion_principal'] ?: 'Ciencias Sociales',
            'resolucion_principal' => $data['resolucion_principal'] ?: '2993/22',
            'firma_director_path' => $data['firma_director_path'] ?? $current['firma_director_path'],
            'sello_oval_path' => $data['sello_oval_path'] ?? $current['sello_oval_path'],
        ]);

        return (int) $current['id'];
    }
}
