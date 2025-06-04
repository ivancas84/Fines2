<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class File extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "file";
        $this->_db = $db;
        $this->setDefault();
        $this->detallePersona_archivo_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
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
    public ?int $detallePersona_archivo_Count = null;

    /** @var DetallePersona[] (ref detalle_persona.archivo _m:o file.id) */
    public array $detallePersona_archivo_ = [];

}
