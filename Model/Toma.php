<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Toma extends Entity
{

    public function __construct()
    {
        $this->_entityName = "toma";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var string|null */
    public ?string $archivo = null;

    /** @var string|null */
    public ?string $comentario = null;

    /** @var bool|null */
    public ?bool $confirmada = null;

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

    /** @var bool|null */
    public ?bool $reclamo = null;

    /** @var string|null */
    public ?string $reemplazo = null;

    /** @var bool|null */
    public ?bool $sin_planillas = null;

    /** @var string|null */
    public ?string $tipo_movimiento = null;

    /** @var Curso|null (fk toma.curso _m:o curso.id) */
    public ?\Fines2\Curso_ $curso_ = null;

    /** @var Persona|null (fk toma.docente _m:o persona.id) */
    public ?\Fines2\Persona_ $docente_ = null;

    /** @var PlanillaDocente|null (fk toma.planilla_docente _m:o planilla_docente.id) */
    public ?\Fines2\PlanillaDocente_ $planilla_docente_ = null;

    /** @var Persona|null (fk toma.reemplazo _m:o persona.id) */
    public ?\Fines2\Persona_ $reemplazo_ = null;

}
