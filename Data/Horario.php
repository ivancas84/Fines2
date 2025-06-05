<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Horario extends Entity
{

    public function __construct()
    {
        $this->_entityName = "horario";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $curso = null;

    /** @var string|null */
    public ?string $dia = null;

    /** @var DateTime|null */
    public ?DateTime $hora_fin = null;

    /** @var DateTime|null */
    public ?DateTime $hora_inicio = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var Curso|null (fk horario.curso _m:o curso.id) */
    public ?\Fines2\Curso_ $curso_ = null;

    /** @var Dia|null (fk horario.dia _m:o dia.id) */
    public ?\Fines2\Dia_ $dia_ = null;

}
