<?php

namespace SqlOrganize\Sql\Fines2;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\Db;
use Exception;
use DateTime;

class Sede extends Entity
{

    public function __construct(Db $db)
    {
        $this->_entityName = "sede";
        $this->_db = $db;
        $this->setDefault();
        $this->comision_ = [];
        $this->designacion_ = [];
        $this->sede_organizacion_ = [];
    }

    public function setFromTree(array $treeData)
    {
    $centro_educativo_ = null;
    $domicilio_ = null;
    $organizacion_ = null;
    $tipo_sede_ = null;
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

    /** @var CentroEducativo|null (fk sede.centro_educativo _m:o centro_educativo.id) */
    public ?CentroEducativo $centro_educativo_ = null;

    /** @var string|null */
    public ?string $centro_educativo__ = null;

    /** @var Domicilio|null (fk sede.domicilio _m:o domicilio.id) */
    public ?Domicilio $domicilio_ = null;

    /** @var string|null */
    public ?string $domicilio__ = null;

    /** @var Sede|null (fk sede.organizacion _m:o sede.id) */
    public ?Sede $organizacion_ = null;

    /** @var string|null */
    public ?string $organizacion__ = null;

    /** @var TipoSede|null (fk sede.tipo_sede _m:o tipo_sede.id) */
    public ?TipoSede $tipo_sede_ = null;

    /** @var string|null */
    public ?string $tipo_sede__ = null;

    /** @var int|null */
    public ?int $comision_Count = null;

    /** @var Comision[] (ref comision.sede _m:o sede.id) */
    public array $comision_ = [];

    /** @var int|null */
    public ?int $designacion_Count = null;

    /** @var Designacion[] (ref designacion.sede _m:o sede.id) */
    public array $designacion_ = [];

    /** @var int|null */
    public ?int $sede_organizacion_Count = null;

    /** @var Sede[] (ref sede.organizacion _m:o sede.id) */
    public array $sede_organizacion_ = [];

}
