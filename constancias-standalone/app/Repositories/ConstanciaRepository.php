<?php

declare(strict_types=1);

namespace ConstanciasApp\Repositories;

use PDO;

final class ConstanciaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO fines_app_constancias (
                establecimiento_id, tipo, titulo, descripcion, clave,
                nombres, apellidos, numero_documento, datos_json,
                origen_sistema, origen_referencia,
                archivo_path, archivo_nombre, mime_type, creado_por
            ) VALUES (
                :establecimiento_id, :tipo, :titulo, :descripcion, :clave,
                :nombres, :apellidos, :numero_documento, :datos_json,
                :origen_sistema, :origen_referencia,
                :archivo_path, :archivo_nombre, :mime_type, :creado_por
            )
        ");
        $stmt->execute([
            'establecimiento_id' => $data['establecimiento_id'] ?? null,
            'tipo' => $data['tipo'],
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'clave' => $data['clave'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'numero_documento' => $data['numero_documento'],
            'datos_json' => json_encode($data['datos'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'origen_sistema' => $data['origen_sistema'] ?? 'constancias',
            'origen_referencia' => $data['origen_referencia'] ?? null,
            'archivo_path' => $data['archivo_path'] ?? null,
            'archivo_nombre' => $data['archivo_nombre'] ?? null,
            'mime_type' => $data['mime_type'] ?? 'application/pdf',
            'creado_por' => $data['creado_por'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updateFile(int $id, string $path, string $name): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE fines_app_constancias
            SET archivo_path = :archivo_path, archivo_nombre = :archivo_nombre, mime_type = 'application/pdf'
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id, 'archivo_path' => $path, 'archivo_nombre' => $name]);
    }

    public function latest(?int $establecimientoId, int $limit = 100): array
    {
        $where = $establecimientoId === null ? '' : 'WHERE establecimiento_id = :establecimiento_id';
        $stmt = $this->pdo->prepare("
            SELECT id, tipo, titulo, descripcion, clave, nombres, apellidos, numero_documento,
                   origen_sistema, origen_referencia, archivo_nombre, creado_en, anulado_en
            FROM fines_app_constancias
            {$where}
            ORDER BY creado_en DESC, id DESC
            LIMIT {$limit}
        ");
        $stmt->execute($establecimientoId === null ? [] : ['establecimiento_id' => $establecimientoId]);

        return $stmt->fetchAll();
    }

    public function findValid(string $id, string $clave): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, e.nombre AS establecimiento_nombre
            FROM fines_app_constancias c
            LEFT JOIN fines_app_establecimientos e ON e.id = c.establecimiento_id
            WHERE c.id = :id AND c.clave = :clave AND c.anulado_en IS NULL
            LIMIT 1
        ");
        $stmt->execute(['id' => $id, 'clave' => $clave]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
