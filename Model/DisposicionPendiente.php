<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class DisposicionPendiente extends Entity
{

    public function __construct()
    {
        $this->_entityName = "disposicion_pendiente";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $alumno = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $modo = null;

    /** @var \Fines2\Alumno|null (fk disposicion_pendiente.alumno _m:o alumno.id) */
    public ?\Fines2\Alumno $alumno_ = null;

    /** @var \Fines2\Disposicion_|null (fk disposicion_pendiente.disposicion _m:o disposicion.id) */
    public ?\Fines2\Disposicion_ $disposicion_ = null;

}
