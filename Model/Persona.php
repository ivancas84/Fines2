<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Persona extends Entity
{

    public function __construct()
    {
        $this->_entityName = "persona";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
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

    /** @var bool|null */
    public ?bool $email_verificado = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_nacimiento = null;

    /** @var string|null */
    public ?string $genero = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var bool|null */
    public ?bool $info_verificada = null;

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

    /** @var bool|null */
    public ?bool $telefono_verificado = null;

    /** @var Domicilio|null (fk persona.domicilio _m:o domicilio.id) */
    public ?\Fines2\Domicilio_ $domicilio_ = null;

    /** @var Alumno|null (ref alumno.persona _o:o persona.id) */
    public ?\Fines2\Alumno_ $Alumno_ = null;

    /** @var int|null */
    public ?int $Designacion_Count = null;

    /** @var Designacion[] (ref designacion.persona _m:o persona.id) */
    public array $Designacion_ = [];

    /** @var int|null */
    public ?int $DetallePersona_Count = null;

    /** @var DetallePersona[] (ref detalle_persona.persona _m:o persona.id) */
    public array $DetallePersona_ = [];

    /** @var int|null */
    public ?int $Email_Count = null;

    /** @var Email[] (ref email.persona _m:o persona.id) */
    public array $Email_ = [];

    /** @var int|null */
    public ?int $Telefono_Count = null;

    /** @var Telefono[] (ref telefono.persona _m:o persona.id) */
    public array $Telefono_ = [];

    /** @var int|null */
    public ?int $Toma_docente_Count = null;

    /** @var Toma[] (ref toma.docente _m:o persona.id) */
    public array $Toma_docente_ = [];

    /** @var int|null */
    public ?int $Toma_reemplazo_Count = null;

    /** @var Toma[] (ref toma.reemplazo _m:o persona.id) */
    public array $Toma_reemplazo_ = [];

}
