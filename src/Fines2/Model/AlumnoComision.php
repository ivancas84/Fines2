<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class AlumnoComision extends Entity
{

    public function __construct()
    {
        $this->_entityName = "alumno_comision";
        $this->_db = \App\Context::getFinesDb();
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

    /** @var Alumno_|null (fk alumno_comision.alumno _m:o alumno.id) */
    public ?Alumno_ $alumno_ = null;

    /** @var Comision_|null (fk alumno_comision.comision _m:o comision.id) */
    public ?Comision_ $comision_ = null;

}
