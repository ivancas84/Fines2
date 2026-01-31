<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class DistribucionHoraria extends Entity
{

    public function __construct()
    {
        $this->_entityName = "distribucion_horaria";
        $this->_db = \App\Context::getFinesDb();
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

    /** @var Disposicion|null (fk distribucion_horaria.disposicion _m:o disposicion.id) */
    public ?Disposicion_ $disposicion_ = null;

}
