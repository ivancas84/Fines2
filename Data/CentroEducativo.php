<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class CentroEducativo extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "centro_educativo";
        $this->_db = $db;
        $this->setDefault();
        $this->sede_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
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
    public ?Domicilio $domicilio_ = null;

    /** @var string|null */
    public ?string $domicilio__ = null;

    /** @var int|null */
    public ?int $sede_Count = null;

    /** @var Sede[] (ref sede.centro_educativo _m:o centro_educativo.id) */
    public array $sede_ = [];

}
