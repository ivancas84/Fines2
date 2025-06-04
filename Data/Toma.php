<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Toma extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "toma";
        $this->_db = $db;
        $this->setDefault();
        $this->asignacionPlanillaDocente_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var string|null */
    public ?string $archivo = null;

    /** @var int|null */
    public ?int $asistencia = null;

    /** @var int|null */
    public ?int $calificacion = null;

    /** @var string|null */
    public ?string $comentario = null;

    /** @var int|null */
    public ?int $confirmada = null;

    /** @var string|null */
    public ?string $curso = null;

    /** @var string|null */
    public ?string $docente = null;

    /** @var string|null */
    public ?string $estado = null;

    /** @var string|null */
    public ?string $estado_contralor = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_toma = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var string|null */
    public ?string $planilla_docente = null;

    /** @var string|null */
    public ?string $reemplazo = null;

    /** @var int|null */
    public ?int $sin_planillas = null;

    /** @var int|null */
    public ?int $temas_tratados = null;

    /** @var string|null */
    public ?string $tipo_movimiento = null;

    /** @var Curso|null (fk toma.curso _m:o curso.id) */
    public ?Curso $curso_ = null;

    /** @var string|null */
    public ?string $curso__ = null;

    /** @var Persona|null (fk toma.docente _m:o persona.id) */
    public ?Persona $docente_ = null;

    /** @var string|null */
    public ?string $docente__ = null;

    /** @var PlanillaDocente|null (fk toma.planilla_docente _m:o planilla_docente.id) */
    public ?PlanillaDocente $planilla_docente_ = null;

    /** @var string|null */
    public ?string $planilla_docente__ = null;

    /** @var Persona|null (fk toma.reemplazo _m:o persona.id) */
    public ?Persona $reemplazo_ = null;

    /** @var string|null */
    public ?string $reemplazo__ = null;

    /** @var int|null */
    public ?int $asignacionPlanillaDocente_Count = null;

    /** @var AsignacionPlanillaDocente[] (ref asignacion_planilla_docente.toma _m:o toma.id) */
    public array $asignacionPlanillaDocente_ = [];

}
