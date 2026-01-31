<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Modalidad extends Entity
{

    public function __construct()
    {
        $this->_entityName = "modalidad";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var int|null */
    public ?int $Comision_Count = null;

    /** @var Comision[] (ref comision.modalidad _m:o modalidad.id) */
    public array $Comision_ = [];

}
