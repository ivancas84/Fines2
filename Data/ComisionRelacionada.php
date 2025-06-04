<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class ComisionRelacionada extends Entity
{

    public function __construct()
    {
        $this->_entityName = "comision_relacionada";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $comision = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $relacion = null;

    /** @var Comision|null (fk comision_relacionada.comision _m:o comision.id) */
    public ?\Fines2\Comision $comision_ = null;

    /** @var Comision|null (fk comision_relacionada.relacion _m:o comision.id) */
    public ?\Fines2\Comision $relacion_ = null;

}
