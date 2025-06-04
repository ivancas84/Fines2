<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class AsignacionPlanillaDocente extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "asignacion_planilla_docente";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $comentario = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var string|null */
    public ?string $planilla_docente = null;

    /** @var int|null */
    public ?int $reclamo = null;

    /** @var string|null */
    public ?string $toma = null;

    /** @var PlanillaDocente|null (fk asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id) */
    public ?PlanillaDocente $planilla_docente_ = null;

    /** @var string|null */
    public ?string $planilla_docente__ = null;

    /** @var Toma|null (fk asignacion_planilla_docente.toma _m:o toma.id) */
    public ?Toma $toma_ = null;

    /** @var string|null */
    public ?string $toma__ = null;

}
