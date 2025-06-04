<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class ComisionRelacionada extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "comision_relacionada";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $comision = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $relacion = null;

    /** @var Comision|null (fk comision_relacionada.comision _m:o comision.id) */
    public ?Comision $comision_ = null;

    /** @var string|null */
    public ?string $comision__ = null;

    /** @var Comision|null (fk comision_relacionada.relacion _m:o comision.id) */
    public ?Comision $relacion_ = null;

    /** @var string|null */
    public ?string $relacion__ = null;

}
