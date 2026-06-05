<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

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
            'origen_sistema' => $data['origen_sistema'] ?? null,
            'origen_referencia' => $data['origen_referencia'] ?? null,
            'archivo_path' => $data['archivo_path'] ?? null,
            'archivo_nombre' => $data['archivo_nombre'] ?? null,
            'mime_type' => $data['mime_type'] ?? 'application/pdf',
            'creado_por' => $data['creado_por'],
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
        $stmt->execute([
            'id' => $id,
            'archivo_path' => $path,
            'archivo_nombre' => $name,
        ]);
    }

    public function byOrigin(string $system, string $reference): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, tipo, titulo, descripcion, clave, nombres, apellidos, numero_documento,
                   archivo_nombre, creado_en, anulado_en
            FROM fines_app_constancias
            WHERE origen_sistema = :origen_sistema
              AND origen_referencia = :origen_referencia
            ORDER BY creado_en DESC, id DESC
        ");
        $stmt->execute([
            'origen_sistema' => $system,
            'origen_referencia' => $reference,
        ]);

        return $stmt->fetchAll();
    }

    public function findValid(string $id, string $clave): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM fines_app_constancias
            WHERE id = :id
              AND clave = :clave
              AND anulado_en IS NULL
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id,
            'clave' => $clave,
        ]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
