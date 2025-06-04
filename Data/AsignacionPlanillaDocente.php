<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class AsignacionPlanillaDocente extends Entity
{

    public function __construct()
    {
        $this->_entityName = "asignacion_planilla_docente";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
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
    public ?\Fines2\PlanillaDocente $planilla_docente_ = null;

    /** @var Toma|null (fk asignacion_planilla_docente.toma _m:o toma.id) */
    public ?\Fines2\Toma $toma_ = null;

}
