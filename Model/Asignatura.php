<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Asignatura extends Entity
{

    public function __construct()
    {
        $this->_entityName = "asignatura";
        $this->_db = \App\Context::getFinesDb();
        $this->setDefault();
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
    public ?int $Disposicion_Count = null;

    /** @var Disposicion[] (ref disposicion.asignatura _m:o asignatura.id) */
    public array $Disposicion_ = [];

}
