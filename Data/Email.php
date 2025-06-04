<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Email extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "email";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {    }    /** @var DateTime|null */
    public ?DateTime $eliminado = null;

    /** @var string|null */
    public ?string $email = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var DateTime|null */
    public ?DateTime $insertado = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var int|null */
    public ?int $verificado = null;

    /** @var Persona|null (fk email.persona _m:o persona.id) */
    public ?Persona $persona_ = null;

    /** @var string|null */
    public ?string $persona__ = null;

}
