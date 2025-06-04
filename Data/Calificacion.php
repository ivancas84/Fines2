<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Calificacion extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "calificacion";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {
    $alumno_ = null;
    $curso_ = null;
    $disposicion_ = null;
    }

    /** @var string|null */
    public ?string $alumno = null;

    /** @var int|null */
    public ?int $archivado = null;

    /** @var float|null */
    public ?float $crec = null;

    /** @var string|null */
    public ?string $curso = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var string|null */
    public ?string $division = null;

    /** @var DateTime|null */
    public ?DateTime $fecha = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var float|null */
    public ?float $nota1 = null;

    /** @var float|null */
    public ?float $nota2 = null;

    /** @var float|null */
    public ?float $nota3 = null;

    /** @var float|null */
    public ?float $nota_final = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var int|null */
    public ?int $porcentaje_asistencia = null;

    /** @var Alumno|null (fk calificacion.alumno _m:o alumno.id) */
    public ?Alumno $alumno_ = null;

    /** @var string|null */
    public ?string $alumno__ = null;

    /** @var Curso|null (fk calificacion.curso _m:o curso.id) */
    public ?Curso $curso_ = null;

    /** @var string|null */
    public ?string $curso__ = null;

    /** @var Disposicion|null (fk calificacion.disposicion _m:o disposicion.id) */
    public ?Disposicion $disposicion_ = null;

    /** @var string|null */
    public ?string $disposicion__ = null;

}
