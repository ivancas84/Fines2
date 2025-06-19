<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Plan extends Entity
{

    public function __construct()
    {
        $this->_entityName = "plan";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->Alumno_ = [];
        $this->Planificacion_ = [];
    }

    /** @var string|null */
    public ?string $distribucion_horaria = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $orientacion = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var string|null */
    public ?string $resolucion = null;

    /** @var int|null */
    public ?int $Alumno_Count = null;

    /** @var Alumno[] (ref alumno.plan _m:o plan.id) */
    public array $Alumno_ = [];

    /** @var int|null */
    public ?int $Planificacion_Count = null;

    /** @var Planificacion[] (ref planificacion.plan _m:o plan.id) */
    public array $Planificacion_ = [];

}
