<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Plan extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "plan";
        $this->_db = $db;
        $this->setDefault();
        $this->alumno_ = [];
        $this->planificacion_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
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
    public ?int $alumno_Count = null;

    /** @var Alumno[] (ref alumno.plan _m:o plan.id) */
    public array $alumno_ = [];

    /** @var int|null */
    public ?int $planificacion_Count = null;

    /** @var Planificacion[] (ref planificacion.plan _m:o plan.id) */
    public array $planificacion_ = [];

}
