<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Designacion extends Entity
{

    public function __construct()
    {
        $this->_entityName = "designacion";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var string|null */
    public ?string $cargo = null;

    /** @var DateTime|null */
    public ?DateTime $desde = null;

    /** @var DateTime|null */
    public ?DateTime $hasta = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var string|null */
    public ?string $sede = null;

    /** @var Cargo|null (fk designacion.cargo _m:o cargo.id) */
    public ?\Fines2\Cargo_ $cargo_ = null;

    /** @var Persona|null (fk designacion.persona _m:o persona.id) */
    public ?\Fines2\Persona_ $persona_ = null;

    /** @var Sede|null (fk designacion.sede _m:o sede.id) */
    public ?\Fines2\Sede_ $sede_ = null;

}
