<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class DistribucionHoraria extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "distribucion_horaria";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {
    $disposicion_ = null;
    }

    /** @var int|null */
    public ?int $dia = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var int|null */
    public ?int $horas_catedra = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var Disposicion|null (fk distribucion_horaria.disposicion _m:o disposicion.id) */
    public ?Disposicion $disposicion_ = null;

    /** @var string|null */
    public ?string $disposicion__ = null;

}
