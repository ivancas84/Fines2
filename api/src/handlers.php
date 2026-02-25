    <?php

// src/api/handlers.php

function handle_personas(?string $id, $dataProvider): void
{
    if ($id === null) {
        // GET /api/personas → list (very basic – you can add filters later)
        $personas = $dataProvider->fetchAllEntitiesByParams('persona', []); // ← all, or add limit/where

        send_json(['data' => $personas->toArray()]);
    }

    // GET /api/personas/xxxx-xxxx-xxxx
    $persona = $dataProvider->fetchEntityByParams('persona', ['id' => $id]);

    if (!$persona || !$persona->id) {
        error_json('Persona no encontrada', 404);
    }


    send_json(['data' => $persona->toArray()]);
}

function handle_comisiones(?string $id, $dataProvider): void
{
    if ($id === null) {
        // List – you can improve later with filters, pagination, etc.
        $comisiones = $dataProvider->fetchAllEntitiesByParams('comision', []);

        $result = array_map(
            fn($c) => $c->toArray(),
            $comisiones
        );

        send_json(['data' => $result]);

    }

    // Single
    $comision = $dataProvider->fetchEntityByParams('comision', ['id' => $id]);

    if (!$comision || !$comision->id) {
        error_json('Comisión no encontrada', 404);
    }

    $data = [
        'id'            => $comision->id,
        'label'         => $comision->getLabel(),
        'division'      => $comision->division,
        'identificacion'=> $comision->identificacion,
        'turno'         => $comision->turno,
        'planificacion' => $comision->planificacion,
        'calendario'    => $comision->calendario,
        'sede'          => $comision->sede,
        'modalidad'     => $comision->modalidad,
        'comision_siguiente' => $comision->comision_siguiente,
        // you can load related planificacion_ if you want
        'planificacion_anio_semestre' => $comision->planificacion_ ? [
            'anio'     => $comision->planificacion_->anio,
            'semestre' => $comision->planificacion_->semestre,
        ] : null,
    ];

    send_json(['data' => $data]);
}