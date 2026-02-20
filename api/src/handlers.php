    <?php

// src/api/handlers.php

function handle_personas(?string $id, $dataProvider): void
{
    if ($id === null) {
        // GET /api/personas → list (very basic – you can add filters later)
        $personas = $dataProvider->fetchAllEntitiesByParams('persona', []); // ← all, or add limit/where

        $result = array_map(fn($p) => [
            'id'               => $p->id,
            'nombres'          => $p->nombres,
            'apellidos'        => $p->apellidos,
            'cuil'             => $p->cuil,
            'numero_documento' => $p->numero_documento,
            'fecha_nacimiento' => $p->fecha_nacimiento?->format('Y-m-d'),
            'email'            => $p->email,
            'telefono'         => $p->telefono,
            // add only what you want to expose
        ], $personas);

        send_json(['data' => $result]);
    }

    // GET /api/personas/xxxx-xxxx-xxxx
    $persona = $dataProvider->fetchEntityByParams('persona', ['id' => $id]);

    if (!$persona || !$persona->id) {
        error_json('Persona no encontrada', 404);
    }

    $data = [
        'id'               => $persona->id,
        'nombres'          => $persona->nombres,
        'apellidos'        => $persona->apellidos,
        'cuil'             => $persona->cuil,
        'numero_documento' => $persona->numero_documento,
        'fecha_nacimiento' => $persona->fecha_nacimiento?->format('Y-m-d'),
        'genero'           => $persona->genero,
        'email'            => $persona->email,
        'telefono'         => $persona->telefono,
        'domicilio'        => $persona->domicilio,
        'localidad'        => $persona->localidad,
        // you can add related data if needed, example:
        // 'alumno' => $persona->Alumno_ ? $persona->Alumno_->toArray() : null,
    ];

    send_json(['data' => $data]);
}

function handle_comisiones(?string $id, $dataProvider): void
{
    if ($id === null) {
        // List – you can improve later with filters, pagination, etc.
        $comisiones = $dataProvider->fetchAllEntitiesByParams('comision', []);

        $result = array_map(fn($c) => [
            'id'            => $c->id,
            'label'         => $c->getLabel(),
            'division'      => $c->division,
            'turno'         => $c->turno,
            'planificacion' => $c->planificacion,
            'sede'          => $c->sede,
            'modalidad'     => $c->modalidad,
        ], $comisiones);

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