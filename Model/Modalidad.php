<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Modalidad extends Entity
{

    public function __construct()
    {
        $this->_entityName = "modalidad";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var int|null */
    public ?int $Comision_Count = null;

    /** @var \Fines2\Comision_[] (ref comision.modalidad _m:o modalidad.id) */
    public array $Comision_ = [];

}
