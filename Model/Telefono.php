<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Telefono extends Entity
{

    public function __construct()
    {
        $this->_entityName = "telefono";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $eliminado = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var string|null */
    public ?string $prefijo = null;

    /** @var string|null */
    public ?string $tipo = null;

    /** @var \Fines2\Persona_|null (fk telefono.persona _m:o persona.id) */
    public ?\Fines2\Persona_ $persona_ = null;

}
