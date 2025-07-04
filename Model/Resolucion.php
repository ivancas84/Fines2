<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Resolucion extends Entity
{

    public function __construct()
    {
        $this->_entityName = "resolucion";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $anio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $tipo = null;

    /** @var int|null */
    public ?int $Alumno_resolucion_inscripcion_Count = null;

    /** @var \Fines2\Alumno[] (ref alumno.resolucion_inscripcion _m:o resolucion.id) */
    public array $Alumno_resolucion_inscripcion_ = [];

}
