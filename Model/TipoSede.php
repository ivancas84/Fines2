<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class TipoSede extends Entity
{

    public function __construct()
    {
        $this->_entityName = "tipo_sede";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $descripcion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $Sede_Count = null;

    /** @var Sede[] (ref sede.tipo_sede _m:o tipo_sede.id) */
    public array $Sede_ = [];

}
