<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class TipoSede extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "tipo_sede";
        $this->_db = $db;
        $this->setDefault();
        $this->sede_ = [];
    }

    public function setFromTree(array $treeData)
    {
    }

    /** @var string|null */
    public ?string $descripcion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $sede_Count = null;

    /** @var Sede[] (ref sede.tipo_sede _m:o tipo_sede.id) */
    public array $sede_ = [];

}
