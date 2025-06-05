<?php


require_once __DIR__ . '/db-config.php';

use \Fines2\TomaDAO;
use \SqlOrganize\Sql\DbMy;


$db = DbMy::getInstance();
$data = $db->CreateDataProvider()->fetchAll("calendario");
print_r($data);
die();
/*

$personas = $db->CreateDataProvider()->fetchEntitiesByIds("persona", '10');

echo "<pre>";

foreach($personas as $persona) {
    print_r($persona->domicilio_->toArray());
}*/

?>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Sede</th>
            <th>Persona</th>
            <th>Documento</th>
            <th>Curso</th>
            <th>Comisión</th>
            <th>Planilla</th>
            <th>Fecha Toma</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tomas as $toma): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($toma->curso_?->comision_?->sede_?->getLabel()) ?>
                </td>
                <td>
                    <?= htmlspecialchars($toma->docente_?->apellidos . ', ' . $toma->docente_?->nombres) ?>
                </td>
                <td>
                    <?= htmlspecialchars($toma->docente_?->numero_documento) ?>
                </td>
                <td>
                    <?= htmlspecialchars($toma->comision_?->curso_?->nombre ?? 'N/A') ?>
                </td>
                <td>
                    <?= htmlspecialchars($toma->comision_?->nombre ?? 'N/A') ?>
                </td>
                <td>
                    <?= htmlspecialchars($toma->planilla_docente_?->nombre ?? 'N/A') ?>
                </td>
                <td>
                    <?= $toma->fecha ? $toma->fecha->format('Y-m-d') : '—' ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
