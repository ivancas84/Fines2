<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class PersonaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function find(string $personaId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nombres, apellidos, numero_documento, cuil, cuil1, cuil2, sexo,
                   dia_nacimiento, mes_nacimiento, anio_nacimiento, telefono, codigo_area,
                   email, email_abc, lugar_nacimiento, nacionalidad, descripcion_domicilio,
                   departamento, localidad, partido
            FROM persona
            WHERE id = :persona_id
        ");
        $stmt->execute(['persona_id' => $personaId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function search(string $search): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, apellidos, nombres, numero_documento, telefono, email, email_abc
            FROM persona
            WHERE LOWER(apellidos) LIKE LOWER(:search)
               OR LOWER(nombres) LIKE LOWER(:search)
               OR LOWER(numero_documento) LIKE LOWER(:search)
               OR LOWER(telefono) LIKE LOWER(:search)
               OR LOWER(email) LIKE LOWER(:search)
               OR LOWER(email_abc) LIKE LOWER(:search)
            ORDER BY apellidos ASC, nombres ASC, numero_documento ASC
            LIMIT 100
        ");
        $stmt->execute(['search' => '%' . $search . '%']);

        return $stmt->fetchAll();
    }

    public function update(string $personaId, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE persona
            SET nombres = :nombres,
                apellidos = :apellidos,
                numero_documento = :numero_documento,
                cuil1 = :cuil1,
                cuil2 = :cuil2,
                sexo = :sexo,
                dia_nacimiento = :dia_nacimiento,
                mes_nacimiento = :mes_nacimiento,
                anio_nacimiento = :anio_nacimiento,
                telefono = :telefono,
                codigo_area = :codigo_area,
                email = :email,
                email_abc = :email_abc,
                lugar_nacimiento = :lugar_nacimiento,
                nacionalidad = :nacionalidad,
                descripcion_domicilio = :descripcion_domicilio,
                departamento = :departamento,
                localidad = :localidad,
                partido = :partido
            WHERE id = :id
        ");

        $stmt->execute(array_merge($data, ['id' => $personaId]));
    }
}
