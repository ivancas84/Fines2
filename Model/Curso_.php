<?php

namespace Fines2;

use \Fines2\Curso;

class Curso_ extends Curso
{
/** @var string|null */
    public ?string $toma_activa = null;

    /** @var Toma|null (fk ficticia curso.toma_activa _o:o toma.id) */
    public ?\Fines2\Toma_ $toma_activa_ = null;


    
    
}

