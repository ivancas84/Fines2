<?php
use Fines2\ComisionDAO;
use Fines2\Curso_;
use Fines2\CursoDAO;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;

require_once '../fines-config.php';


/** @var Curso_[] */ $cursos = CursoDAO::CursosActivosConTomasActivasByCalendario(CALENDARIO_ID_ACTUAL);

usort($cursos, function($a, $b) {
    $aApellido = $a->toma_activa_->docente_->apellidos ?? '';
    $bApellido = $b->toma_activa_->docente_->apellidos ?? '';
    $aNombre   = $a->toma_activa_->docente_->nombres ?? '';
    $bNombre   = $b->toma_activa_->docente_->nombres ?? '';

    // First compare by apellido
    $cmp = strcasecmp($aApellido, $bApellido);
    if ($cmp !== 0) {
        return $cmp;
    }

    // If apellidos are equal, compare by nombre
    return strcasecmp($aNombre, $bNombre);
});

foreach($cursos as $curso){

    echo "
    nrodocumento: " . $curso->toma_activa_->docente_?->numero_documento . ",
    nombres: '" . addslashes($curso->toma_activa_->docente_?->nombres) . "',
    apellidos: '" . addslashes($curso->toma_activa_->docente_?->apellidos) . "',
    fecha_nacimiento: '" . $curso->toma_activa_->docente_?->fecha_nacimiento?->format("Y-m-d") . "',
    c_sexo: '" . (str_starts_with($curso->toma_activa_->docente_?->genero ?? '', 'F') ? 'F' : 'M') . "',
    carga_horaria_semanal: " . ($curso->horas_catedra ?? $curso->disposicion_->horas_catedra) . ",
    fecha_designacion: '" . $curso->toma_activa_->fecha_toma?->format("Y-m-d") . "',
    designacion: '" . $curso->disposicion_->getLabel() . "',
<br><br>";
}
?>
