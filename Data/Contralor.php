<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Contralor extends Entity
{

    public function __construct()
    {
        $this->_entityName = "contralor";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
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
    public ?string $planilla_docente = null;

    /** @var PlanillaDocente|null (fk contralor.planilla_docente _m:o planilla_docente.id) */
    public ?\Fines2\PlanillaDocente $planilla_docente_ = null;

}
