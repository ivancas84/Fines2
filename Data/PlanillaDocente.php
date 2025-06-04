<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class PlanillaDocente extends Entity
{

    public function __construct()
    {
        $this->_entityName = "planilla_docente";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->AsignacionPlanillaDocente_ = [];
        $this->Contralor_ = [];
        $this->Toma_ = [];
    }

    /** @var DateTime|null */
    public ?DateTime $fecha_consejo = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_contralor = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var int|null */
    public ?int $AsignacionPlanillaDocente_Count = null;

    /** @var AsignacionPlanillaDocente[] (ref asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id) */
    public array $AsignacionPlanillaDocente_ = [];

    /** @var int|null */
    public ?int $Contralor_Count = null;

    /** @var Contralor[] (ref contralor.planilla_docente _m:o planilla_docente.id) */
    public array $Contralor_ = [];

    /** @var int|null */
    public ?int $Toma_Count = null;

    /** @var Toma[] (ref toma.planilla_docente _m:o planilla_docente.id) */
    public array $Toma_ = [];

}
