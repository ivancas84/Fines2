<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Calificacion extends Entity
{

    public function __construct()
    {
        $this->_entityName = "calificacion";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $alumno = null;

    /** @var bool|null */
    public ?bool $archivado = null;

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
    public ?Alumno_ $alumno_ = null;

    /** @var Curso|null (fk calificacion.curso _m:o curso.id) */
    public ?Curso_ $curso_ = null;

    /** @var Disposicion|null (fk calificacion.disposicion _m:o disposicion.id) */
    public ?Disposicion_ $disposicion_ = null;

}
