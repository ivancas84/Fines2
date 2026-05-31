<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class PlanRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo
            ->query('SELECT id, orientacion, resolucion FROM plan ORDER BY orientacion ASC, resolucion ASC')
            ->fetchAll();
    }
}
