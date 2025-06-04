<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Curso extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "curso";
        $this->_db = $db;
        $this->setDefault();
        $this->calificacion_ = [];
        $this->horario_ = [];
        $this->toma_ = [];
    }

    public function setFromTree(array $treeData)
    {
    $asignatura_ = null;
    $comision_ = null;
    $disposicion_ = null;
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

    /** @var Asignatura|null (fk curso.asignatura _m:o asignatura.id) */
    public ?Asignatura $asignatura_ = null;

    /** @var string|null */
    public ?string $asignatura__ = null;

    /** @var Comision|null (fk curso.comision _m:o comision.id) */
    public ?Comision $comision_ = null;

    /** @var string|null */
    public ?string $comision__ = null;

    /** @var Disposicion|null (fk curso.disposicion _m:o disposicion.id) */
    public ?Disposicion $disposicion_ = null;

    /** @var string|null */
    public ?string $disposicion__ = null;

    /** @var int|null */
    public ?int $calificacion_Count = null;

    /** @var Calificacion[] (ref calificacion.curso _m:o curso.id) */
    public array $calificacion_ = [];

    /** @var int|null */
    public ?int $horario_Count = null;

    /** @var Horario[] (ref horario.curso _m:o curso.id) */
    public array $horario_ = [];

    /** @var int|null */
    public ?int $toma_Count = null;

    /** @var Toma[] (ref toma.curso _m:o curso.id) */
    public array $toma_ = [];

}
