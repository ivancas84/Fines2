<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Cargo extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "cargo";
        $this->_db = $db;
        $this->setDefault();
        $this->designacion_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $descripcion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $designacion_Count = null;

    /** @var Designacion[] (ref designacion.cargo _m:o cargo.id) */
    public array $designacion_ = [];

}
