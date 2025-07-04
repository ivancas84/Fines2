<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Curso extends Entity
{

    public function __construct()
    {
        $this->_entityName = "curso";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var string|null */
    public ?string $asignatura = null;

    /** @var string|null */
    public ?string $codigo = null;

    /** @var string|null */
    public ?string $comision = null;

    /** @var string|null */
    public ?string $descripcion_horario = null;

    /** @var string|null */
    public ?string $disposicion = null;

    /** @var int|null */
    public ?int $horas_catedra = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $ige = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var \Fines2\Asignatura|null (fk curso.asignatura _m:o asignatura.id) */
    public ?\Fines2\Asignatura $asignatura_ = null;

    /** @var \Fines2\Comision_|null (fk curso.comision _m:o comision.id) */
    public ?\Fines2\Comision_ $comision_ = null;

    /** @var \Fines2\Disposicion_|null (fk curso.disposicion _m:o disposicion.id) */
    public ?\Fines2\Disposicion_ $disposicion_ = null;

    /** @var int|null */
    public ?int $Calificacion_Count = null;

    /** @var \Fines2\Calificacion_[] (ref calificacion.curso _m:o curso.id) */
    public array $Calificacion_ = [];

    /** @var int|null */
    public ?int $Horario_Count = null;

    /** @var \Fines2\Horario[] (ref horario.curso _m:o curso.id) */
    public array $Horario_ = [];

    /** @var int|null */
    public ?int $Toma_Count = null;

    /** @var \Fines2\Toma[] (ref toma.curso _m:o curso.id) */
    public array $Toma_ = [];

}
