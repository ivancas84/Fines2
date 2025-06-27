<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Cargo extends Entity
{

    public function __construct()
    {
        $this->_entityName = "cargo";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $descripcion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $Designacion_Count = null;

    /** @var Designacion[] (ref designacion.cargo _m:o cargo.id) */
    public array $Designacion_ = [];

}
