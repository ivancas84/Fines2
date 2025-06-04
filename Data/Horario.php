<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Horario extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "horario";
        $this->_db = $db;
        $this->setDefault();
    }

    public function setFromTree(array $treeData)
    {
    $curso_ = null;
    $dia_ = null;
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
    public ?Curso $curso_ = null;

    /** @var string|null */
    public ?string $curso__ = null;

    /** @var Dia|null (fk horario.dia _m:o dia.id) */
    public ?Dia $dia_ = null;

    /** @var string|null */
    public ?string $dia__ = null;

}
