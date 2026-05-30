<?php

namespace Fines7\Repositories;

use PDO;

class PlanRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $sql = "
            SELECT id, orientacion, resolucion
            FROM plan
            ORDER BY orientacion ASC, resolucion ASC
        ";

        return $this->pdo->query($sql)->fetchAll();
    }
}
