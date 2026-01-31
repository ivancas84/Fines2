<?php

namespace Fines2\Model;

use \Fines2\Model\Curso;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Curso_ extends Curso
{
    /** @var string|null */
    public ?string $toma_activa = null;

    /** @var Toma|null (fk ficticia curso.toma_activa _o:o toma.id) */
    public ?Toma_ $toma_activa_ = null;

    public function getLabel(): string
    {
        return ($this->disposicion_?->getLabel() ?? "?") . " - " .
        ($this->comision_?->sede_?->getLabel() ?? "?") . " " . 
        ($this->comision_?->pfid ?? "S/N");
    }
}

