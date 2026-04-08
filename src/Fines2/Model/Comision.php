<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Comision extends Entity
{

    public function __construct()
    {
        $this->_entityName = "comision";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var bool|null */
    public ?bool $apertura = null;

    /** @var bool|null */
    public ?bool $autorizada = null;

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

    /** @var bool|null */
    public ?bool $publicada = null;

    /** @var string|null */
    public ?string $sede = null;

    /** @var string|null */
    public ?string $turno = null;

    /** @var Calendario_|null (fk comision.calendario _m:o calendario.id) */
    public ?Calendario_ $calendario_ = null;

    /** @var Comision_|null (fk comision.comision_siguiente _m:o comision.id) */
    public ?Comision_ $comision_siguiente_ = null;

    /** @var Modalidad|null (fk comision.modalidad _m:o modalidad.id) */
    public ?Modalidad_ $modalidad_ = null;

    /** @var Planificacion_|null (fk comision.planificacion _m:o planificacion.id) */
    public ?Planificacion_ $planificacion_ = null;

    /** @var Sede_|null (fk comision.sede _m:o sede.id) */
    public ?Sede_ $sede_ = null;

    /** @var int|null */
    public ?int $AlumnoComision_Count = null;

    /** @var AlumnoComision_[] (ref alumno_comision.comision _m:o comision.id) */
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
