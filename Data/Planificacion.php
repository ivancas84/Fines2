<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Planificacion extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "planificacion";
        $this->_db = $db;
        $this->setDefault();
        $this->comision_ = [];
        $this->disposicion_ = [];
    }

    public function setFromTree(array $treeData)
    {
    $plan_ = null;
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
    public ?Plan $plan_ = null;

    /** @var string|null */
    public ?string $plan__ = null;

    /** @var int|null */
    public ?int $comision_Count = null;

    /** @var Comision[] (ref comision.planificacion _m:o planificacion.id) */
    public array $comision_ = [];

    /** @var int|null */
    public ?int $disposicion_Count = null;

    /** @var Disposicion[] (ref disposicion.planificacion _m:o planificacion.id) */
    public array $disposicion_ = [];

}
