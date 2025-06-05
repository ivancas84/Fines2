<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Disposicion extends Entity
{

    public function __construct()
    {
        $this->_entityName = "disposicion";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
        $this->Calificacion_ = [];
        $this->Curso_ = [];
        $this->DisposicionPendiente_ = [];
        $this->DistribucionHoraria_ = [];
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
    public ?\Fines2\Asignatura $asignatura_ = null;

    /** @var Planificacion|null (fk disposicion.planificacion _m:o planificacion.id) */
    public ?\Fines2\Planificacion $planificacion_ = null;

    /** @var int|null */
    public ?int $Calificacion_Count = null;

    /** @var Calificacion[] (ref calificacion.disposicion _m:o disposicion.id) */
    public array $Calificacion_ = [];

    /** @var int|null */
    public ?int $Curso_Count = null;

    /** @var Curso[] (ref curso.disposicion _m:o disposicion.id) */
    public array $Curso_ = [];

    /** @var int|null */
    public ?int $DisposicionPendiente_Count = null;

    /** @var DisposicionPendiente[] (ref disposicion_pendiente.disposicion _m:o disposicion.id) */
    public array $DisposicionPendiente_ = [];

    /** @var int|null */
    public ?int $DistribucionHoraria_Count = null;

    /** @var DistribucionHoraria[] (ref distribucion_horaria.disposicion _m:o disposicion.id) */
    public array $DistribucionHoraria_ = [];

}
