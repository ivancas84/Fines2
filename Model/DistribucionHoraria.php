<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class DistribucionHoraria extends Entity
{

    public function __construct()
    {
        $this->_entityName = "distribucion_horaria";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $dia = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var int|null */
    public ?int $horas_catedra = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var \Fines2\Disposicion_|null (fk distribucion_horaria.disposicion _m:o disposicion.id) */
    public ?\Fines2\Disposicion_ $disposicion_ = null;

}
