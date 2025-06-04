<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Calendario extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "calendario";
        $this->_db = $db;
        $this->setDefault();
        $this->comision_ = [];
    }

    public function setFromTree(array $treeData)
    {
    }

    /** @var DateTime|null */
    public ?DateTime $anio = null;

    /** @var string|null */
    public ?string $descripcion = null;

    /** @var DateTime|null */
    public ?DateTime $fin = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $inicio = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var int|null */
    public ?int $semestre = null;

    /** @var int|null */
    public ?int $comision_Count = null;

    /** @var Comision[] (ref comision.calendario _m:o calendario.id) */
    public array $comision_ = [];

}
