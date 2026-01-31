<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class AsignacionPlanillaDocente extends Entity
{

    public function __construct()
    {
        $this->_entityName = "asignacion_planilla_docente";
        $this->_db = \App\Context::getFinesDb();
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

    /** @var bool|null */
    public ?bool $reclamo = null;

    /** @var string|null */
    public ?string $toma = null;

    /** @var PlanillaDocente|null (fk asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id) */
    public ?PlanillaDocente_ $planilla_docente_ = null;

    /** @var Toma|null (fk asignacion_planilla_docente.toma _m:o toma.id) */
    public ?Toma_ $toma_ = null;

}
