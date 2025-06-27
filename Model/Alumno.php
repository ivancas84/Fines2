<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Alumno extends Entity
{

    public function __construct()
    {
        $this->_entityName = "alumno";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $adeuda_deudores = null;

    /** @var string|null */
    public ?string $adeuda_legajo = null;

    /** @var string|null */
    public ?string $anio_ingreso = null;

    /** @var int|null */
    public ?int $anio_inscripcion = null;

    /** @var bool|null */
    public ?bool $anio_inscripcion_completo = null;

    /** @var string|null */
    public ?string $comentarios = null;

    /** @var bool|null */
    public ?bool $confirmado_direccion = null;

    /** @var DateTime|null */
    public ?DateTime $creado = null;

    /** @var string|null */
    public ?string $documentacion_inscripcion = null;

    /** @var string|null */
    public ?string $establecimiento_inscripcion = null;

    /** @var string|null */
    public ?string $estado_inscripcion = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_titulacion = null;

    /** @var string|null */
    public ?string $folio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $libro = null;

    /** @var string|null */
    public ?string $libro_folio = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var string|null */
    public ?string $persona = null;

    /** @var string|null */
    public ?string $plan = null;

    /** @var bool|null */
    public ?bool $previas_completas = null;

    /** @var string|null */
    public ?string $resolucion_inscripcion = null;

    /** @var int|null */
    public ?int $semestre_ingreso = null;

    /** @var int|null */
    public ?int $semestre_inscripcion = null;

    /** @var bool|null */
    public ?bool $tiene_certificado = null;

    /** @var bool|null */
    public ?bool $tiene_constancia = null;

    /** @var bool|null */
    public ?bool $tiene_dni = null;

    /** @var bool|null */
    public ?bool $tiene_partida = null;

    /** @var Persona|null (fk alumno.persona _o:o persona.id) */
    public ?\Fines2\Persona_ $persona_ = null;

    /** @var Plan|null (fk alumno.plan _m:o plan.id) */
    public ?\Fines2\Plan_ $plan_ = null;

    /** @var Resolucion|null (fk alumno.resolucion_inscripcion _m:o resolucion.id) */
    public ?\Fines2\Resolucion_ $resolucion_inscripcion_ = null;

    /** @var int|null */
    public ?int $AlumnoComision_Count = null;

    /** @var AlumnoComision[] (ref alumno_comision.alumno _m:o alumno.id) */
    public array $AlumnoComision_ = [];

    /** @var int|null */
    public ?int $Calificacion_Count = null;

    /** @var Calificacion[] (ref calificacion.alumno _m:o alumno.id) */
    public array $Calificacion_ = [];

    /** @var int|null */
    public ?int $DisposicionPendiente_Count = null;

    /** @var DisposicionPendiente[] (ref disposicion_pendiente.alumno _m:o alumno.id) */
    public array $DisposicionPendiente_ = [];

}
