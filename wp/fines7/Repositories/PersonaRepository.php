<?php

namespace Fines7\Repositories;

use PDO;

class PersonaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function search(string $search): array
    {
        $sql = "
            SELECT
                id,
                apellidos,
                nombres,
                numero_documento,
                telefono,
                email,
                email_abc
            FROM persona
            WHERE LOWER(apellidos) LIKE LOWER(:search)
               OR LOWER(nombres) LIKE LOWER(:search)
               OR LOWER(numero_documento) LIKE LOWER(:search)
               OR LOWER(telefono) LIKE LOWER(:search)
               OR LOWER(email) LIKE LOWER(:search)
               OR LOWER(email_abc) LIKE LOWER(:search)
            ORDER BY apellidos ASC, nombres ASC, numero_documento ASC
            LIMIT 100
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'search' => '%' . $search . '%',
        ]);

        return $stmt->fetchAll();
    }
}
