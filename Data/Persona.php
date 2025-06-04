<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Persona extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "persona";
        $this->_db = $db;
        $this->setDefault();
        $this->designacion_ = [];
        $this->detallePersona_ = [];
        $this->email_ = [];
        $this->telefono_ = [];
        $this->toma_docente_ = [];
        $this->toma_reemplazo_ = [];
    }

    

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var int|null */
    public ?int $anio_nacimiento = null;

    /** @var string|null */
    public ?string $apellidos = null;

    /** @var string|null */
    public ?string $apodo = null;

    /** @var string|null */
    public ?string $codigo_area = null;

    /** @var string|null */
    public ?string $cuil = null;

    /** @var int|null */
    public ?int $cuil1 = null;

    /** @var int|null */
    public ?int $cuil2 = null;

    /** @var string|null */
    public ?string $departamento = null;

    /** @var string|null */
    public ?string $descripcion_domicilio = null;

    /** @var int|null */
    public ?int $dia_nacimiento = null;

    /** @var string|null */
    public ?string $domicilio = null;

    /** @var string|null */
    public ?string $email = null;

    /** @var string|null */
    public ?string $email_abc = null;

    /** @var int|null */
    public ?int $email_verificado = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_nacimiento = null;

    /** @var string|null */
    public ?string $genero = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $info_verificada = null;

    /** @var string|null */
    public ?string $localidad = null;

    /** @var string|null */
    public ?string $lugar_nacimiento = null;

    /** @var int|null */
    public ?int $mes_nacimiento = null;

    /** @var string|null */
    public ?string $nacionalidad = null;

    /** @var string|null */
    public ?string $nombres = null;

    /** @var string|null */
    public ?string $numero_documento = null;

    /** @var string|null */
    public ?string $partido = null;

    /** @var int|null */
    public ?int $sexo = null;

    /** @var string|null */
    public ?string $telefono = null;

    /** @var int|null */
    public ?int $telefono_verificado = null;

    /** @var Domicilio|null (fk persona.domicilio _m:o domicilio.id) */
    public ?Domicilio $domicilio_ = null;

    /** @var string|null */
    public ?string $domicilio__ = null;

    /** @var Alumno|null (ref alumno.persona _o:o persona.id) */
    public ?Alumno $alumno_ = null;

    /** @var int|null */
    public ?int $designacion_Count = null;

    /** @var Designacion[] (ref designacion.persona _m:o persona.id) */
    public array $designacion_ = [];

    /** @var int|null */
    public ?int $detallePersona_Count = null;

    /** @var DetallePersona[] (ref detalle_persona.persona _m:o persona.id) */
    public array $detallePersona_ = [];

    /** @var int|null */
    public ?int $email_Count = null;

    /** @var Email[] (ref email.persona _m:o persona.id) */
    public array $email_ = [];

    /** @var int|null */
    public ?int $telefono_Count = null;

    /** @var Telefono[] (ref telefono.persona _m:o persona.id) */
    public array $telefono_ = [];

    /** @var int|null */
    public ?int $toma_docente_Count = null;

    /** @var Toma[] (ref toma.docente _m:o persona.id) */
    public array $toma_docente_ = [];

    /** @var int|null */
    public ?int $toma_reemplazo_Count = null;

    /** @var Toma[] (ref toma.reemplazo _m:o persona.id) */
    public array $toma_reemplazo_ = [];

}
