<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Planificacion extends Entity
{

    public function __construct()
    {
        $this->_entityName = "planificacion";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->Comision_ = [];
        $this->Disposicion_ = [];
    }

    /** @var string|null */
    public ?string $anio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var string|null */
    public ?string $plan = null;

    /** @var string|null */
    public ?string $semestre = null;

    /** @var Plan|null (fk planificacion.plan _m:o plan.id) */
    public ?\Fines2\Plan $plan_ = null;

    /** @var int|null */
    public ?int $Comision_Count = null;

    /** @var Comision[] (ref comision.planificacion _m:o planificacion.id) */
    public array $Comision_ = [];

    /** @var int|null */
    public ?int $Disposicion_Count = null;

    /** @var Disposicion[] (ref disposicion.planificacion _m:o planificacion.id) */
    public array $Disposicion_ = [];

}
