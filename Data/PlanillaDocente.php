<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class PlanillaDocente extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "planilla_docente";
        $this->_db = $db;
        $this->setDefault();
        $this->asignacionPlanillaDocente_ = [];
        $this->contralor_ = [];
        $this->toma_ = [];
    }

    public function setFromTree(array $treeData)
    {
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
    public ?int $asignacionPlanillaDocente_Count = null;

    /** @var AsignacionPlanillaDocente[] (ref asignacion_planilla_docente.planilla_docente _m:o planilla_docente.id) */
    public array $asignacionPlanillaDocente_ = [];

    /** @var int|null */
    public ?int $contralor_Count = null;

    /** @var Contralor[] (ref contralor.planilla_docente _m:o planilla_docente.id) */
    public array $contralor_ = [];

    /** @var int|null */
    public ?int $toma_Count = null;

    /** @var Toma[] (ref toma.planilla_docente _m:o planilla_docente.id) */
    public array $toma_ = [];

}
