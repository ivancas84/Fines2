<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Asignatura extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "asignatura";
        $this->_db = $db;
        $this->setDefault();
        $this->curso_ = [];
        $this->disposicion_ = [];
    }

    public function setFromTree(array $treeData)
    {
    }

    /** @var string|null */
    public ?string $clasificacion = null;

    /** @var string|null */
    public ?string $codigo = null;

    /** @var string|null */
    public ?string $formacion = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $perfil = null;

    /** @var int|null */
    public ?int $curso_Count = null;

    /** @var Curso[] (ref curso.asignatura _m:o asignatura.id) */
    public array $curso_ = [];

    /** @var int|null */
    public ?int $disposicion_Count = null;

    /** @var Disposicion[] (ref disposicion.asignatura _m:o asignatura.id) */
    public array $disposicion_ = [];

}
