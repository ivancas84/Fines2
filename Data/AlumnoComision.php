<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class AlumnoComision extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "alumno_comision";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {
    $alumno_ = null;
    $comision_ = null;
    }

    /** @var int|null */
    public ?int $activo = null;

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

    /** @var Alumno|null (fk alumno_comision.alumno _m:o alumno.id) */
    public ?Alumno $alumno_ = null;

    /** @var string|null */
    public ?string $alumno__ = null;

    /** @var Comision|null (fk alumno_comision.comision _m:o comision.id) */
    public ?Comision $comision_ = null;

    /** @var string|null */
    public ?string $comision__ = null;

}
