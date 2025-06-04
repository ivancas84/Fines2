<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Modalidad extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "modalidad";
        $this->_db = $db;
        $this->setDefault();
        $this->comision_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var int|null */
    public ?int $comision_Count = null;

    /** @var Comision[] (ref comision.modalidad _m:o modalidad.id) */
    public array $comision_ = [];

}
