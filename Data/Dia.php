<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Dia extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "dia";
        $this->_db = $db;
        $this->setDefault();
        $this->horario_ = [];
    }

    public function setFromTree(array $treeData)
    {
    }

    /** @var string|null */
    public ?string $dia = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $numero = null;

    /** @var int|null */
    public ?int $horario_Count = null;

    /** @var Horario[] (ref horario.dia _m:o dia.id) */
    public array $horario_ = [];

}
