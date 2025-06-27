<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class File extends Entity
{

    public function __construct()
    {
        $this->_entityName = "file";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $content = null;

    /** @var DateTime|null */
    public ?DateTime $created = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $size = null;

    /** @var string|null */
    public ?string $type = null;

    /** @var int|null */
    public ?int $DetallePersona_archivo_Count = null;

    /** @var DetallePersona[] (ref detalle_persona.archivo _m:o file.id) */
    public array $DetallePersona_archivo_ = [];

}
