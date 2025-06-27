<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class CentroEducativo extends Entity
{

    public function __construct()
    {
        $this->_entityName = "centro_educativo";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $cue = null;

    /** @var string|null */
    public ?string $domicilio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var Domicilio|null (fk centro_educativo.domicilio _m:o domicilio.id) */
    public ?\Fines2\Domicilio_ $domicilio_ = null;

    /** @var int|null */
    public ?int $Sede_Count = null;

    /** @var Sede[] (ref sede.centro_educativo _m:o centro_educativo.id) */
    public array $Sede_ = [];

}
