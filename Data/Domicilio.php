<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Domicilio extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "domicilio";
        $this->_db = $db;
        $this->setDefault();
        $this->centroEducativo_ = [];
        $this->persona_ = [];
        $this->sede_ = [];
    }

    public function setFromTree(array $treeData)
    {    }    /** @var string|null */
    public ?string $barrio = null;

    /** @var string|null */
    public ?string $calle = null;

    /** @var string|null */
    public ?string $departamento = null;

    /** @var string|null */
    public ?string $entre = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $localidad = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $piso = null;

    /** @var int|null */
    public ?int $centroEducativo_Count = null;

    /** @var CentroEducativo[] (ref centro_educativo.domicilio _m:o domicilio.id) */
    public array $centroEducativo_ = [];

    /** @var int|null */
    public ?int $persona_Count = null;

    /** @var Persona[] (ref persona.domicilio _m:o domicilio.id) */
    public array $persona_ = [];

    /** @var int|null */
    public ?int $sede_Count = null;

    /** @var Sede[] (ref sede.domicilio _m:o domicilio.id) */
    public array $sede_ = [];

}
