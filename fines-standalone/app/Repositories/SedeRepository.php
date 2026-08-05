<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class SedeRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(string $sort = 'numero', string $order = 'asc'): array
    {
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->query("
            SELECT sede.id,
                   sede.numero,
                   sede.nombre,
                   COALESCE(centro_educativo.nombre, '') AS cens,
                   sede.fecha_traspaso,
                   TRIM(CONCAT(
                       COALESCE(domicilio.calle, ''),
                       IF(NULLIF(TRIM(domicilio.numero), '') IS NOT NULL, CONCAT(' N°', domicilio.numero), ''),
                       IF(NULLIF(TRIM(domicilio.entre), '') IS NOT NULL, CONCAT(' e/', domicilio.entre), ''),
                       IF(NULLIF(TRIM(domicilio.barrio), '') IS NOT NULL, CONCAT(' ', domicilio.barrio), ''),
                       IF(NULLIF(TRIM(domicilio.localidad), '') IS NOT NULL, CONCAT(' ', domicilio.localidad), '')
                   )) AS domicilio_label,
                   COALESCE(referentes.referentes_label, 'Sin Referentes') AS referentes_label
            FROM sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN centro_educativo ON centro_educativo.id = sede.centro_educativo
            LEFT JOIN (
                SELECT designacion.sede,
                       GROUP_CONCAT(
                           CONCAT_WS(
                               ' ',
                               NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), ''),
                               NULLIF(TRIM(persona.telefono), '')
                           )
                           ORDER BY persona.apellidos, persona.nombres
                           SEPARATOR ', '
                       ) AS referentes_label
                FROM designacion
                INNER JOIN persona ON persona.id = designacion.persona
                WHERE designacion.cargo = '1' AND designacion.hasta IS NULL
                GROUP BY designacion.sede
            ) referentes ON referentes.sede = sede.id
            ORDER BY {$orderBy}
        ");

        return $stmt->fetchAll();
    }

    public function byId(string $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT sede.id,
                   sede.numero,
                   sede.nombre,
                   sede.observaciones,
                   sede.alta,
                   sede.baja,
                   sede.domicilio AS domicilio_id,
                   sede.tipo_sede AS tipo_sede_id,
                   sede.centro_educativo AS centro_educativo_id,
                   sede.fecha_traspaso,
                   sede.organizacion AS organizacion_id,
                   sede.pfid,
                   sede.pfid_organizacion,
                   COALESCE(centro_educativo.nombre, '') AS cens,
                   domicilio.calle AS domicilio_calle,
                   domicilio.numero AS domicilio_numero,
                   domicilio.entre AS domicilio_entre,
                   domicilio.piso AS domicilio_piso,
                   domicilio.departamento AS domicilio_departamento,
                   domicilio.barrio AS domicilio_barrio,
                   domicilio.localidad AS domicilio_localidad
            FROM sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN centro_educativo ON centro_educativo.id = sede.centro_educativo
            WHERE sede.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{
     *   numero: string,
     *   nombre: string,
     *   observaciones: ?string,
     *   fecha_traspaso: ?string,
     *   tipo_sede: ?string,
     *   centro_educativo: ?string,
     *   pfid: ?string,
     *   domicilio: ?array{
     *     calle: string,
     *     numero: string,
     *     entre: ?string,
     *     piso: ?string,
     *     departamento: ?string,
     *     barrio: ?string,
     *     localidad: string
     *   }
     * } $data
     */
    public function create(array $data): string
    {
        $this->pdo->beginTransaction();
        try {
            $domicilioId = null;
            if ($data['domicilio'] !== null) {
                $domicilioId = $this->insertDomicilio($data['domicilio']);
            }

            $id = uniqid();
            $stmt = $this->pdo->prepare("
                INSERT INTO sede (
                    id, numero, nombre, observaciones, domicilio, tipo_sede,
                    centro_educativo, fecha_traspaso, pfid
                ) VALUES (
                    :id, :numero, :nombre, :observaciones, :domicilio, :tipo_sede,
                    :centro_educativo, :fecha_traspaso, :pfid
                )
            ");
            $stmt->execute([
                'id' => $id,
                'numero' => $data['numero'],
                'nombre' => $data['nombre'],
                'observaciones' => $data['observaciones'],
                'domicilio' => $domicilioId,
                'tipo_sede' => $data['tipo_sede'],
                'centro_educativo' => $data['centro_educativo'],
                'fecha_traspaso' => $data['fecha_traspaso'],
                'pfid' => $data['pfid'],
            ]);

            $this->pdo->commit();

            return $id;
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    /**
     * @param array{
     *   numero: string,
     *   nombre: string,
     *   observaciones: ?string,
     *   fecha_traspaso: ?string,
     *   tipo_sede: ?string,
     *   centro_educativo: ?string,
     *   pfid: ?string,
     *   domicilio: ?array{
     *     calle: string,
     *     numero: string,
     *     entre: ?string,
     *     piso: ?string,
     *     departamento: ?string,
     *     barrio: ?string,
     *     localidad: string
     *   }
     * } $data
     */
    public function update(string $id, array $data): void
    {
        $sede = $this->byId($id);
        if ($sede === null) {
            throw new \RuntimeException('La sede no existe.');
        }

        $this->pdo->beginTransaction();
        try {
            $domicilioId = trim((string) ($sede['domicilio_id'] ?? ''));
            if ($data['domicilio'] !== null) {
                if ($domicilioId !== '') {
                    $this->updateDomicilio($domicilioId, $data['domicilio']);
                } else {
                    $domicilioId = $this->insertDomicilio($data['domicilio']);
                }
            }

            $stmt = $this->pdo->prepare("
                UPDATE sede
                SET numero = :numero,
                    nombre = :nombre,
                    observaciones = :observaciones,
                    domicilio = :domicilio,
                    tipo_sede = :tipo_sede,
                    centro_educativo = :centro_educativo,
                    fecha_traspaso = :fecha_traspaso,
                    pfid = :pfid
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $id,
                'numero' => $data['numero'],
                'nombre' => $data['nombre'],
                'observaciones' => $data['observaciones'],
                'domicilio' => $domicilioId !== '' ? $domicilioId : null,
                'tipo_sede' => $data['tipo_sede'],
                'centro_educativo' => $data['centro_educativo'],
                'fecha_traspaso' => $data['fecha_traspaso'],
                'pfid' => $data['pfid'],
            ]);

            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    public function designacionesBySede(string $sedeId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT designacion.id,
                   designacion.desde,
                   designacion.hasta,
                   designacion.cargo AS cargo_id,
                   designacion.persona AS persona_id,
                   designacion.alta,
                   designacion.pfid,
                   cargo.descripcion AS cargo_descripcion,
                   persona.nombres AS persona_nombres,
                   persona.apellidos AS persona_apellidos,
                   persona.numero_documento AS persona_documento,
                   persona.telefono AS persona_telefono
            FROM designacion
            LEFT JOIN cargo ON cargo.id = designacion.cargo
            LEFT JOIN persona ON persona.id = designacion.persona
            WHERE designacion.sede = :sede
            ORDER BY
                CASE WHEN designacion.hasta IS NULL THEN 0 ELSE 1 END ASC,
                designacion.desde DESC,
                designacion.alta DESC
        ");
        $stmt->execute(['sede' => $sedeId]);

        return $stmt->fetchAll();
    }

    /** @return list<array{id: string, label: string}> */
    public function cargosOptions(): array
    {
        $rows = $this->pdo
            ->query('SELECT id, descripcion FROM cargo ORDER BY descripcion ASC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): array => [
            'id' => (string) $row['id'],
            'label' => (string) ($row['descripcion'] ?? $row['id']),
        ], $rows);
    }

    /** @return list<array{id: string, label: string}> */
    public function tiposSedeOptions(): array
    {
        $rows = $this->pdo
            ->query('SELECT id, descripcion FROM tipo_sede ORDER BY descripcion ASC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): array => [
            'id' => (string) $row['id'],
            'label' => (string) ($row['descripcion'] ?? $row['id']),
        ], $rows);
    }

    /** @return list<array{id: string, label: string}> */
    public function centrosEducativosOptions(): array
    {
        $rows = $this->pdo
            ->query('SELECT id, nombre, cue FROM centro_educativo ORDER BY nombre ASC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $label = trim((string) ($row['nombre'] ?? ''));
            $cue = trim((string) ($row['cue'] ?? ''));
            if ($cue !== '') {
                $label = $label !== '' ? "{$label} ({$cue})" : $cue;
            }

            return [
                'id' => (string) $row['id'],
                'label' => $label !== '' ? $label : (string) $row['id'],
            ];
        }, $rows);
    }

    /**
     * @param array{
     *   cargo: string,
     *   dni: string,
     *   desde: ?string,
     *   hasta: ?string
     * } $data
     */
    public function addDesignacion(string $sedeId, array $data): string
    {
        if ($this->byId($sedeId) === null) {
            throw new \RuntimeException('La sede no existe.');
        }

        $personaId = $this->personaIdByDni($data['dni']);
        $this->assertCargoExists($data['cargo']);

        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO designacion (id, sede, persona, cargo, desde, hasta)
            VALUES (:id, :sede, :persona, :cargo, :desde, :hasta)
        ");
        $stmt->execute([
            'id' => $id,
            'sede' => $sedeId,
            'persona' => $personaId,
            'cargo' => $data['cargo'],
            'desde' => $data['desde'],
            'hasta' => $data['hasta'],
        ]);

        return $id;
    }

    /**
     * @param list<array{id: string, cargo: string, desde: ?string, hasta: ?string}> $rows
     */
    public function updateDesignaciones(string $sedeId, array $rows): void
    {
        if ($this->byId($sedeId) === null) {
            throw new \RuntimeException('La sede no existe.');
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("
                UPDATE designacion
                SET cargo = :cargo,
                    desde = :desde,
                    hasta = :hasta
                WHERE id = :id AND sede = :sede
            ");

            foreach ($rows as $row) {
                $this->assertCargoExists($row['cargo']);
                $stmt->execute([
                    'id' => $row['id'],
                    'sede' => $sedeId,
                    'cargo' => $row['cargo'],
                    'desde' => $row['desde'],
                    'hasta' => $row['hasta'],
                ]);
            }

            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    public function deleteDesignacion(string $sedeId, string $designacionId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM designacion
            WHERE id = :id AND sede = :sede
        ");
        $stmt->execute([
            'id' => $designacionId,
            'sede' => $sedeId,
        ]);

        if ($stmt->rowCount() < 1) {
            throw new \RuntimeException('La designación no existe en esta sede.');
        }
    }

    public function personaIdByDni(string $dni): string
    {
        $dni = preg_replace('/\D+/', '', $dni) ?? '';
        if (strlen($dni) < 7 || strlen($dni) > 8) {
            throw new \InvalidArgumentException('El DNI debe tener 7 u 8 dígitos.');
        }

        // Buscar con y sin ceros a la izquierda (algunos registros históricos varían).
        $candidates = array_values(array_unique([
            $dni,
            str_pad($dni, 8, '0', STR_PAD_LEFT),
            ltrim($dni, '0') !== '' ? ltrim($dni, '0') : $dni,
        ]));

        $placeholders = implode(', ', array_map(static fn (int $i): string => ":d{$i}", array_keys($candidates)));
        $params = [];
        foreach ($candidates as $i => $value) {
            $params["d{$i}"] = $value;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, numero_documento
            FROM persona
            WHERE numero_documento IN ({$placeholders})
            LIMIT 1
        ");
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            throw new \RuntimeException(
                "No se encontró una persona con DNI {$dni}. Cargá la persona primero en Personas y luego reintentá la designación.",
            );
        }

        return (string) $row['id'];
    }

    /**
     * @param array{
     *   calle: string,
     *   numero: string,
     *   entre: ?string,
     *   piso: ?string,
     *   departamento: ?string,
     *   barrio: ?string,
     *   localidad: string
     * } $data
     */
    private function insertDomicilio(array $data): string
    {
        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO domicilio (id, calle, numero, entre, piso, departamento, barrio, localidad)
            VALUES (:id, :calle, :numero, :entre, :piso, :departamento, :barrio, :localidad)
        ");
        $stmt->execute([
            'id' => $id,
            'calle' => $data['calle'],
            'numero' => $data['numero'],
            'entre' => $data['entre'],
            'piso' => $data['piso'],
            'departamento' => $data['departamento'],
            'barrio' => $data['barrio'],
            'localidad' => $data['localidad'],
        ]);

        return $id;
    }

    /**
     * @param array{
     *   calle: string,
     *   numero: string,
     *   entre: ?string,
     *   piso: ?string,
     *   departamento: ?string,
     *   barrio: ?string,
     *   localidad: string
     * } $data
     */
    private function updateDomicilio(string $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE domicilio
            SET calle = :calle,
                numero = :numero,
                entre = :entre,
                piso = :piso,
                departamento = :departamento,
                barrio = :barrio,
                localidad = :localidad
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'calle' => $data['calle'],
            'numero' => $data['numero'],
            'entre' => $data['entre'],
            'piso' => $data['piso'],
            'departamento' => $data['departamento'],
            'barrio' => $data['barrio'],
            'localidad' => $data['localidad'],
        ]);
    }

    private function assertCargoExists(string $cargoId): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM cargo WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $cargoId]);
        if ($stmt->fetchColumn() === false) {
            throw new \InvalidArgumentException('El cargo seleccionado no existe.');
        }
    }

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        return match ($sort) {
            'nombre' => "sede.nombre {$direction}, sede.numero ASC",
            'cens' => "COALESCE(centro_educativo.nombre, '') {$direction}, sede.numero ASC",
            'fecha_traspaso' => "sede.fecha_traspaso {$direction}, sede.numero ASC",
            default => "sede.numero {$direction}, sede.nombre ASC",
        };
    }
}
