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
            SELECT id, nombres, apellidos, numero_documento, cuil1, cuil2, sexo,
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

    public function findByDocumento(string $numeroDocumento): ?array
    {
        $dni = preg_replace('/\D+/', '', $numeroDocumento) ?? '';
        if ($dni === '') {
            return null;
        }

        $candidates = array_values(array_unique(array_filter([
            $dni,
            str_pad($dni, 8, '0', STR_PAD_LEFT),
            ltrim($dni, '0') !== '' ? ltrim($dni, '0') : null,
        ])));

        $placeholders = implode(', ', array_map(static fn (int $i): string => ":d{$i}", array_keys($candidates)));
        $params = [];
        foreach ($candidates as $i => $value) {
            $params["d{$i}"] = $value;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, nombres, apellidos, numero_documento
            FROM persona
            WHERE numero_documento IN ({$placeholders})
            LIMIT 1
        ");
        $stmt->execute($params);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findByEmailAbc(string $emailAbc): ?array
    {
        $email = trim($emailAbc);
        if ($email === '') {
            return null;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, nombres, apellidos, numero_documento
            FROM persona
            WHERE email_abc IS NOT NULL AND LOWER(email_abc) = LOWER(:email)
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * @param array{
     *   nombres: string,
     *   apellidos: ?string,
     *   numero_documento: string,
     *   cuil1: ?int,
     *   cuil2: ?int,
     *   sexo: ?int,
     *   dia_nacimiento: ?int,
     *   mes_nacimiento: ?int,
     *   anio_nacimiento: ?int,
     *   telefono: ?string,
     *   codigo_area: ?string,
     *   email: ?string,
     *   email_abc: ?string,
     *   lugar_nacimiento: ?string,
     *   nacionalidad: ?string,
     *   descripcion_domicilio: ?string,
     *   departamento: ?string,
     *   localidad: ?string,
     *   partido: ?string
     * } $data
     */
    public function create(array $data): string
    {
        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO persona (
                id, nombres, apellidos, numero_documento, cuil1, cuil2, sexo,
                dia_nacimiento, mes_nacimiento, anio_nacimiento, telefono, codigo_area,
                email, email_abc, lugar_nacimiento, nacionalidad, descripcion_domicilio,
                departamento, localidad, partido
            ) VALUES (
                :id, :nombres, :apellidos, :numero_documento, :cuil1, :cuil2, :sexo,
                :dia_nacimiento, :mes_nacimiento, :anio_nacimiento, :telefono, :codigo_area,
                :email, :email_abc, :lugar_nacimiento, :nacionalidad, :descripcion_domicilio,
                :departamento, :localidad, :partido
            )
        ");
        $stmt->execute(array_merge($data, ['id' => $id]));

        return $id;
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
