<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Contralor extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "contralor";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {    }    /** @var DateTime|null */
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
    public ?PlanillaDocente $planilla_docente_ = null;

    /** @var string|null */
    public ?string $planilla_docente__ = null;

}
