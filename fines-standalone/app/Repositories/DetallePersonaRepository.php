<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class DetallePersonaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byPersona(string $personaId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT detalle_persona.id,
                   detalle_persona.descripcion,
                   detalle_persona.fecha,
                   detalle_persona.tipo,
                   detalle_persona.asunto,
                   `file`.content AS file_content
            FROM detalle_persona
            LEFT JOIN `file` ON `file`.id = detalle_persona.archivo
            WHERE detalle_persona.persona = :persona_id
            ORDER BY detalle_persona.fecha DESC, detalle_persona.creado DESC
        ");
        $stmt->execute(['persona_id' => $personaId]);

        return $stmt->fetchAll();
    }
}
