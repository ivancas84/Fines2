<?php

namespace Fines2;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Sede extends Entity
{

    public function __construct()
    {
        $this->_entityName = "sede";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var DateTime|null */
    public ?DateTime $alta = null;

    /** @var DateTime|null */
    public ?DateTime $baja = null;

    /** @var string|null */
    public ?string $centro_educativo = null;

    /** @var string|null */
    public ?string $domicilio = null;

    /** @var DateTime|null */
    public ?DateTime $fecha_traspaso = null;

    /** @var string|null */
    public ?string $id = null;

    /** @var string|null */
    public ?string $nombre = null;

    /** @var string|null */
    public ?string $numero = null;

    /** @var string|null */
    public ?string $observaciones = null;

    /** @var string|null */
    public ?string $organizacion = null;

    /** @var string|null */
    public ?string $pfid = null;

    /** @var string|null */
    public ?string $pfid_organizacion = null;

    /** @var string|null */
    public ?string $tipo_sede = null;

    /** @var \Fines2\CentroEducativo_|null (fk sede.centro_educativo _m:o centro_educativo.id) */
    public ?\Fines2\CentroEducativo_ $centro_educativo_ = null;

    /** @var \Fines2\Domicilio_|null (fk sede.domicilio _m:o domicilio.id) */
    public ?\Fines2\Domicilio_ $domicilio_ = null;

    /** @var \Fines2\Sede_|null (fk sede.organizacion _m:o sede.id) */
    public ?\Fines2\Sede_ $organizacion_ = null;

    /** @var \Fines2\TipoSede|null (fk sede.tipo_sede _m:o tipo_sede.id) */
    public ?\Fines2\TipoSede $tipo_sede_ = null;

    /** @var int|null */
    public ?int $Comision_Count = null;

    /** @var \Fines2\Comision_[] (ref comision.sede _m:o sede.id) */
    public array $Comision_ = [];

    /** @var int|null */
    public ?int $Designacion_Count = null;

    /** @var \Fines2\Designacion_[] (ref designacion.sede _m:o sede.id) */
    public array $Designacion_ = [];

    /** @var int|null */
    public ?int $Sede_organizacion_Count = null;

    /** @var \Fines2\Sede_[] (ref sede.organizacion _m:o sede.id) */
    public array $Sede_organizacion_ = [];

}
