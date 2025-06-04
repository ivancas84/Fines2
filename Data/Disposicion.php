<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Disposicion extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "disposicion";
        $this->_db = $db;
        $this->setDefault();
        $this->calificacion_ = [];
        $this->curso_ = [];
        $this->disposicionPendiente_ = [];
        $this->distribucionHoraria_ = [];
    }

    public function setFromTree(array $treeData)
    {
    $asignatura_ = null;
    $planificacion_ = null;
    }

    /** @var string|null */
    public ?string $asignatura = null;

    /** @var int|null */
    public ?int $horas_catedra = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var int|null */
    public ?int $orden_informe_coordinacion_distrital = null;

    /** @var string|null */
    public ?string $planificacion = null;

    /** @var Asignatura|null (fk disposicion.asignatura _m:o asignatura.id) */
    public ?Asignatura $asignatura_ = null;

    /** @var string|null */
    public ?string $asignatura__ = null;

    /** @var Planificacion|null (fk disposicion.planificacion _m:o planificacion.id) */
    public ?Planificacion $planificacion_ = null;

    /** @var string|null */
    public ?string $planificacion__ = null;

    /** @var int|null */
    public ?int $calificacion_Count = null;

    /** @var Calificacion[] (ref calificacion.disposicion _m:o disposicion.id) */
    public array $calificacion_ = [];

    /** @var int|null */
    public ?int $curso_Count = null;

    /** @var Curso[] (ref curso.disposicion _m:o disposicion.id) */
    public array $curso_ = [];

    /** @var int|null */
    public ?int $disposicionPendiente_Count = null;

    /** @var DisposicionPendiente[] (ref disposicion_pendiente.disposicion _m:o disposicion.id) */
    public array $disposicionPendiente_ = [];

    /** @var int|null */
    public ?int $distribucionHoraria_Count = null;

    /** @var DistribucionHoraria[] (ref distribucion_horaria.disposicion _m:o disposicion.id) */
    public array $distribucionHoraria_ = [];

}
