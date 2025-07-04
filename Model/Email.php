<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Email extends Entity
{

    public function __construct()
    {
        $this->_entityName = "email";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $eliminado = null;

    /** @var string|null */
    public ?string $email = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var bool|null */
    public ?bool $verificado = null;

    /** @var \Fines2\Persona_|null (fk email.persona _m:o persona.id) */
    public ?\Fines2\Persona_ $persona_ = null;

}
