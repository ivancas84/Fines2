<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class DetallePersona extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "detalle_persona";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $archivo = null;

    /** @var string|null */
    public ?string $asunto = null;

    /** @var DateTime|null */
    public ?DateTime $creado = null;

    /** @var string|null */
    public ?string $descripcion = null;

    /** @var DateTime|null */
    public ?DateTime $fecha = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var string|null */
    public ?string $tipo = null;

    /** @var File|null (fk detalle_persona.archivo _m:o file.id) */
    public ?File $archivo_ = null;

    /** @var string|null */
    public ?string $archivo__ = null;

    /** @var Persona|null (fk detalle_persona.persona _m:o persona.id) */
    public ?Persona $persona_ = null;

    /** @var string|null */
    public ?string $persona__ = null;

}
