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
                archivo_path, archivo_nombre, mime_type, creado_por
            ) VALUES (
                :establecimiento_id, :tipo, :titulo, :descripcion, :clave,
                :nombres, :apellidos, :numero_documento, :datos_json,
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

    public function recentDuplicate(array $data, int $seconds = 30): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, clave
            FROM fines_app_constancias
            WHERE tipo = :tipo
              AND numero_documento = :numero_documento
              AND titulo = :titulo
              AND creado_por <=> :creado_por
              AND creado_en >= DATE_SUB(NOW(), INTERVAL {$seconds} SECOND)
              AND anulado_en IS NULL
            ORDER BY creado_en DESC, id DESC
            LIMIT 1
        ");
        $stmt->execute([
            'tipo' => $data['tipo'],
            'numero_documento' => $data['numero_documento'],
            'titulo' => $data['titulo'],
            'creado_por' => $data['creado_por'] ?? null,
        ]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function search(?int $establecimientoId, string $term, int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        [$where, $params] = $this->searchWhere($establecimientoId, $term);

        $count = $this->pdo->prepare("SELECT COUNT(*) FROM fines_app_constancias {$where}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare("
            SELECT id, tipo, titulo, descripcion, clave, nombres, apellidos, numero_documento, datos_json,
                   archivo_nombre, creado_en, anulado_en
            FROM fines_app_constancias
            {$where}
            ORDER BY creado_en DESC, id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ");
        $stmt->execute($params);

        return [
            'rows' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'pages' => $pages,
        ];
    }

    private function searchWhere(?int $establecimientoId, string $term): array
    {
        $where = [];
        $params = [];
        if ($establecimientoId !== null) {
            $where[] = 'establecimiento_id = :establecimiento_id';
            $params['establecimiento_id'] = $establecimientoId;
        }

        $term = trim($term);
        if ($term !== '') {
            $where[] = '(nombres LIKE :term OR apellidos LIKE :term OR numero_documento LIKE :term)';
            $params['term'] = '%' . $term . '%';
        }

        return [$where === [] ? '' : 'WHERE ' . implode(' AND ', $where), $params];
    }

    public function claveExists(string $clave): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM fines_app_constancias WHERE clave = :clave LIMIT 1');
        $stmt->execute(['clave' => strtoupper($clave)]);

        return $stmt->fetchColumn() !== false;
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

    public function findValidByClave(string $clave): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, e.nombre AS establecimiento_nombre
            FROM fines_app_constancias c
            LEFT JOIN fines_app_establecimientos e ON e.id = c.establecimiento_id
            WHERE c.clave = :clave AND c.anulado_en IS NULL
            LIMIT 1
        ");
        $stmt->execute(['clave' => strtoupper($clave)]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
