<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Domicilio extends Entity
{

    public function __construct()
    {
        $this->_entityName = "domicilio";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
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
    public ?int $CentroEducativo_Count = null;

    /** @var CentroEducativo[] (ref centro_educativo.domicilio _m:o domicilio.id) */
    public array $CentroEducativo_ = [];

    /** @var int|null */
    public ?int $Persona_Count = null;

    /** @var Persona[] (ref persona.domicilio _m:o domicilio.id) */
    public array $Persona_ = [];

    /** @var int|null */
    public ?int $Sede_Count = null;

    /** @var Sede[] (ref sede.domicilio _m:o domicilio.id) */
    public array $Sede_ = [];

}
