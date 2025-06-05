<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Comision extends Entity
{

    public function __construct()
    {
        $this->_entityName = "comision";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->AlumnoComision_ = [];
        $this->Comision_comision_siguiente_ = [];
        $this->ComisionRelacionada_ = [];
        $this->ComisionRelacionada_relacion_ = [];
        $this->Curso_ = [];
    }

    /** @var DateTime|null */
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
    public ?\Fines2\Calendario_ $calendario_ = null;

    /** @var Comision|null (fk comision.comision_siguiente _m:o comision.id) */
    public ?\Fines2\Comision_ $comision_siguiente_ = null;

    /** @var Modalidad|null (fk comision.modalidad _m:o modalidad.id) */
    public ?\Fines2\Modalidad_ $modalidad_ = null;

    /** @var Planificacion|null (fk comision.planificacion _m:o planificacion.id) */
    public ?\Fines2\Planificacion_ $planificacion_ = null;

    /** @var Sede|null (fk comision.sede _m:o sede.id) */
    public ?\Fines2\Sede_ $sede_ = null;

    /** @var int|null */
    public ?int $AlumnoComision_Count = null;

    /** @var AlumnoComision[] (ref alumno_comision.comision _m:o comision.id) */
    public array $AlumnoComision_ = [];

    /** @var int|null */
    public ?int $Comision_comision_siguiente_Count = null;

    /** @var Comision[] (ref comision.comision_siguiente _m:o comision.id) */
    public array $Comision_comision_siguiente_ = [];

    /** @var int|null */
    public ?int $ComisionRelacionada_Count = null;

    /** @var ComisionRelacionada[] (ref comision_relacionada.comision _m:o comision.id) */
    public array $ComisionRelacionada_ = [];

    /** @var int|null */
    public ?int $ComisionRelacionada_relacion_Count = null;

    /** @var ComisionRelacionada[] (ref comision_relacionada.relacion _m:o comision.id) */
    public array $ComisionRelacionada_relacion_ = [];

    /** @var int|null */
    public ?int $Curso_Count = null;

    /** @var Curso[] (ref curso.comision _m:o comision.id) */
    public array $Curso_ = [];

}
