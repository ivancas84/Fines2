<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class AlumnoComision extends Entity
{

    public function __construct()
    {
        $this->_entityName = "alumno_comision";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var bool|null */
    public ?bool $activo = null;

    /** @var string|null */
    public ?string $alumno = null;

    /** @var string|null */
    public ?string $comision = null;

    /** @var DateTime|null */
    public ?DateTime $creado = null;

    /** @var string|null */
    public ?string $estado = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var int|null */
    public ?int $pfid = null;

    /** @var \Fines2\Alumno|null (fk alumno_comision.alumno _m:o alumno.id) */
    public ?\Fines2\Alumno $alumno_ = null;

    /** @var \Fines2\Comision_|null (fk alumno_comision.comision _m:o comision.id) */
    public ?\Fines2\Comision_ $comision_ = null;

}
