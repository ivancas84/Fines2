<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Comision extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "comision";
        $this->_db = $db;
        $this->setDefault();
        $this->alumnoComision_ = [];
        $this->comision_comision_siguiente_ = [];
        $this->comisionRelacionada_ = [];
        $this->comisionRelacionada_relacion_ = [];
        $this->curso_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var int|null */
    public ?int $apertura = null;

    /** @var int|null */
    public ?int $autorizada = null;

    /** @var string|null */
    public ?string $calendario = null;

    /** @var string|null */
    public ?string $comentario = null;

    /** @var string|null */
    public ?string $comision_siguiente = null;

    /** @var string|null */
    public ?string $configuracion = null;

    /** @var string|null */
    public ?string $division = null;

    /** @var string|null */
    public ?string $estado = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $identificacion = null;

    /** @var string|null */
    public ?string $modalidad = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var string|null */
    public ?string $planificacion = null;

    /** @var int|null */
    public ?int $publicada = null;

    /** @var string|null */
    public ?string $sede = null;

    /** @var string|null */
    public ?string $turno = null;

    /** @var Calendario|null (fk comision.calendario _m:o calendario.id) */
    public ?Calendario $calendario_ = null;

    /** @var string|null */
    public ?string $calendario__ = null;

    /** @var Comision|null (fk comision.comision_siguiente _m:o comision.id) */
    public ?Comision $comision_siguiente_ = null;

    /** @var string|null */
    public ?string $comision_siguiente__ = null;

    /** @var Modalidad|null (fk comision.modalidad _m:o modalidad.id) */
    public ?Modalidad $modalidad_ = null;

    /** @var string|null */
    public ?string $modalidad__ = null;

    /** @var Planificacion|null (fk comision.planificacion _m:o planificacion.id) */
    public ?Planificacion $planificacion_ = null;

    /** @var string|null */
    public ?string $planificacion__ = null;

    /** @var Sede|null (fk comision.sede _m:o sede.id) */
    public ?Sede $sede_ = null;

    /** @var string|null */
    public ?string $sede__ = null;

    /** @var int|null */
    public ?int $alumnoComision_Count = null;

    /** @var AlumnoComision[] (ref alumno_comision.comision _m:o comision.id) */
    public array $alumnoComision_ = [];

    /** @var int|null */
    public ?int $comision_comision_siguiente_Count = null;

    /** @var Comision[] (ref comision.comision_siguiente _m:o comision.id) */
    public array $comision_comision_siguiente_ = [];

    /** @var int|null */
    public ?int $comisionRelacionada_Count = null;

    /** @var ComisionRelacionada[] (ref comision_relacionada.comision _m:o comision.id) */
    public array $comisionRelacionada_ = [];

    /** @var int|null */
    public ?int $comisionRelacionada_relacion_Count = null;

    /** @var ComisionRelacionada[] (ref comision_relacionada.relacion _m:o comision.id) */
    public array $comisionRelacionada_relacion_ = [];

    /** @var int|null */
    public ?int $curso_Count = null;

    /** @var Curso[] (ref curso.comision _m:o comision.id) */
    public array $curso_ = [];

}
