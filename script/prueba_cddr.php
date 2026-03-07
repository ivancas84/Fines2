<?php

use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\DataAccess\ComisionDAO;
use Fines2\DataAccess\DisposicionDAO;
use SqlOrganize\Utils\ValueTypesUtils;

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

$pfid = "10142";

/** @var Disposicion_[] */
$disposiciones = DisposicionDAO::disposicionesDivision($pfid);

/** @var array<string, Disposicion_> */
$disposicionesPorId = ValueTypesUtils::dictOfObjByPropertyNames($disposiciones, "id");

/** @var CollectComisionIdsResult */
$ccir = ComisionDAO::collectComisionIdsByPfid($pfid);

/** @var Alumno_[] */
$alumnos = AlumnoDAO::alumnosComisiones($ccir->id_comisiones);

/** @var array<string, Alumno_> */
$alumnosPorId = ValueTypesUtils::dictOfObjByPropertyNames($alumnos, "id");

/** @var array<string, array> */
$calificaciones = CalificacionDAO::calificacionesAprobadasAlumnosDisposiciones(
    array_keys($alumnosPorId),
    array_keys($disposicionesPorId)
);

// construir matriz alumno-disposición
$tabla = [];

foreach ($calificaciones as $r) {

    $alumno = $r['alumno'];
    $disp   = $r['disposicion'];

    $nota = "";

    if ((float)$r['nota_final'] >= 7) {
        $nota = $r['nota_final'];
    } elseif ((float)$r['crec'] > 4) {
        $nota = $r['crec'] . "c";
    }

    $tabla[$alumno][$disp] = $nota;
}

?>

<table border="1" cellpadding="6" cellspacing="0">

<thead>
<tr>
<th>Alumno</th>

<?php foreach ($disposicionesPorId as $dispId => $disp): ?>

<th><?= htmlspecialchars($disp->getLabel()) ?></th>

<?php endforeach; ?>

</tr>
</thead>

<tbody>

<?php foreach ($alumnosPorId as $alumnoId => $alumno): ?>

<tr>

<td>
<?= htmlspecialchars($alumno->persona_->getLabel()) ?>
</td>

<?php foreach ($disposicionesPorId as $dispId => $disp): ?>

<td style="text-align:center">

<?= $tabla[$alumnoId][$dispId] ?? "" ?>

</td>

<?php endforeach; ?>

</tr>

<?php endforeach; ?>

</tbody>

</table>