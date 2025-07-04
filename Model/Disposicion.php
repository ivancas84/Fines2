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

    /** @var \Fines2\Asignatura|null (fk disposicion.asignatura _m:o asignatura.id) */
    public ?\Fines2\Asignatura $asignatura_ = null;

    /** @var \Fines2\Planificacion_|null (fk disposicion.planificacion _m:o planificacion.id) */
    public ?\Fines2\Planificacion_ $planificacion_ = null;

    /** @var int|null */
    public ?int $Calificacion_Count = null;

    /** @var \Fines2\Calificacion_[] (ref calificacion.disposicion _m:o disposicion.id) */
    public array $Calificacion_ = [];

    /** @var int|null */
    public ?int $Curso_Count = null;

    /** @var \Fines2\Curso_[] (ref curso.disposicion _m:o disposicion.id) */
    public array $Curso_ = [];

    /** @var int|null */
    public ?int $DisposicionPendiente_Count = null;

    /** @var \Fines2\DisposicionPendiente[] (ref disposicion_pendiente.disposicion _m:o disposicion.id) */
    public array $DisposicionPendiente_ = [];

    /** @var int|null */
    public ?int $DistribucionHoraria_Count = null;

    /** @var \Fines2\DistribucionHoraria[] (ref distribucion_horaria.disposicion _m:o disposicion.id) */
    public array $DistribucionHoraria_ = [];

}
