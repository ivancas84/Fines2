<?php

namespace Fines2\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Toma extends Entity
{

    public function __construct()
    {
        $this->_entityName = "toma";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var string|null */
    public ?string $archivo = null;

    /** @var string|null */
    public ?string $comentario = null;

    /** @var string|null */
    public ?string $curso = null;

    /** @var string|null */
    public ?string $docente = null;

    /** @var string|null */
    public ?string $estado = null;

    /** @var string|null */
    public ?string $estado_contralor = null;

    /** @var string|null */
    public ?string $estado_planilla = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_toma = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var string|null */
    public ?string $planilla_docente = null;

    /** @var bool|null */
    public ?bool $reclamo = null;

    /** @var string|null */
    public ?string $reemplazo = null;

    /** @var string|null */
    public ?string $tipo_movimiento = null;

    /** @var Curso|null (fk toma.curso _m:o curso.id) */
    public ?Curso_ $curso_ = null;

    /** @var Persona|null (fk toma.docente _m:o persona.id) */
    public ?Persona_ $docente_ = null;

    /** @var PlanillaDocente|null (fk toma.planilla_docente _m:o planilla_docente.id) */
    public ?PlanillaDocente_ $planilla_docente_ = null;

    /** @var Persona|null (fk toma.reemplazo _m:o persona.id) */
    public ?Persona_ $reemplazo_ = null;

    /** @var int|null */
    public ?int $AsignacionPlanillaDocente_Count = null;

    /** @var AsignacionPlanillaDocente[] (ref asignacion_planilla_docente.toma _m:o toma.id) */
    public array $AsignacionPlanillaDocente_ = [];

}
