<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class DetallePersona extends Entity
{

    public function __construct()
    {
        $this->_entityName = "detalle_persona";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var string|null */
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
    public ?File_ $archivo_ = null;

    /** @var Persona|null (fk detalle_persona.persona _m:o persona.id) */
    public ?Persona_ $persona_ = null;

}
