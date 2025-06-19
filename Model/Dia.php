<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Dia extends Entity
{

    public function __construct()
    {
        $this->_entityName = "dia";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->Horario_ = [];
    }

    /** @var string|null */
    public ?string $dia = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $numero = null;

    /** @var int|null */
    public ?int $Horario_Count = null;

    /** @var Horario[] (ref horario.dia _m:o dia.id) */
    public array $Horario_ = [];

}
