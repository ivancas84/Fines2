<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class DisposicionPendiente extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "disposicion_pendiente";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {
    $alumno_ = null;
    $disposicion_ = null;
    }

    /** @var string|null */
    public ?string $alumno = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $modo = null;

    /** @var Alumno|null (fk disposicion_pendiente.alumno _m:o alumno.id) */
    public ?Alumno $alumno_ = null;

    /** @var string|null */
    public ?string $alumno__ = null;

    /** @var Disposicion|null (fk disposicion_pendiente.disposicion _m:o disposicion.id) */
    public ?Disposicion $disposicion_ = null;

    /** @var string|null */
    public ?string $disposicion__ = null;

}
