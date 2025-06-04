<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Calendario extends Entity
{

    public function __construct()
    {
        $this->_entityName = "calendario";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->Comision_ = [];
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
    public ?int $Comision_Count = null;

    /** @var Comision[] (ref comision.calendario _m:o calendario.id) */
    public array $Comision_ = [];

}
