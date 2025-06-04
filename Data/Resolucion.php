<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Resolucion extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "resolucion";
        $this->_db = $db;
        $this->setDefault();
        $this->alumno_resolucion_inscripcion_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var DateTime|null */
    public ?DateTime $anio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $tipo = null;

    /** @var int|null */
    public ?int $alumno_resolucion_inscripcion_Count = null;

    /** @var Alumno[] (ref alumno.resolucion_inscripcion _m:o resolucion.id) */
    public array $alumno_resolucion_inscripcion_ = [];

}
