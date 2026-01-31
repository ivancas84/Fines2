<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class ComisionRelacionada extends Entity
{

    public function __construct()
    {
        $this->_entityName = "comision_relacionada";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $comision = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $relacion = null;

    /** @var Comision|null (fk comision_relacionada.comision _m:o comision.id) */
    public ?Comision_ $comision_ = null;

    /** @var Comision|null (fk comision_relacionada.relacion _m:o comision.id) */
    public ?Comision_ $relacion_ = null;

}
