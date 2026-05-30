<?php

namespace Fines7\Repositories;

use PDO;

class PersonaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function find(string $personaId): ?array
    {
        $sql = "
            SELECT
                id,
                nombres,
                apellidos,
                numero_documento,
                cuil,
                cuil1,
                cuil2,
                sexo,
                dia_nacimiento,
                mes_nacimiento,
                anio_nacimiento,
                telefono,
                codigo_area,
                email,
                email_abc,
                lugar_nacimiento,
                nacionalidad,
                descripcion_domicilio,
                departamento,
                localidad,
                partido
            FROM persona
            WHERE id = :persona_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['persona_id' => $personaId]);
        $persona = $stmt->fetch();

        return $persona ?: null;
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

    public function update(string $personaId, array $data): void
    {
        $sql = "
            UPDATE persona
            SET
                nombres = :nombres,
                apellidos = :apellidos,
                numero_documento = :numero_documento,
                cuil = :cuil,
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
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $personaId,
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'numero_documento' => $data['numero_documento'],
            'cuil' => $data['cuil'],
            'cuil1' => $data['cuil1'],
            'cuil2' => $data['cuil2'],
            'sexo' => $data['sexo'],
            'dia_nacimiento' => $data['dia_nacimiento'],
            'mes_nacimiento' => $data['mes_nacimiento'],
            'anio_nacimiento' => $data['anio_nacimiento'],
            'telefono' => $data['telefono'],
            'codigo_area' => $data['codigo_area'],
            'email' => $data['email'],
            'email_abc' => $data['email_abc'],
            'lugar_nacimiento' => $data['lugar_nacimiento'],
            'nacionalidad' => $data['nacionalidad'],
            'descripcion_domicilio' => $data['descripcion_domicilio'],
            'departamento' => $data['departamento'],
            'localidad' => $data['localidad'],
            'partido' => $data['partido'],
        ]);
    }
}
