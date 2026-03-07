<style>
.rdd2-info{
    margin-bottom:15px;
    padding:10px;
    background:#f5f5f5;
    border:1px solid #ddd;
    width:fit-content;
}

.rdd2-table{
    border-collapse: collapse;
    font-size:13px;
}

.rdd2-table th,
.rdd2-table td{
    border:1px solid #ccc;
    padding:6px 8px;
}

.rdd2-table th{
    text-align:center;
}

.sem1{
    background:#e3f2fd;
}

.sem2{
    background:#fff3cd;
}

.ok{
    background:#c8e6c9;
    font-weight:bold;
}

.missing{
    background:#ffcdd2;
}
</style>


<div class="rdd2-info">

<b>Cantidad de comisiones:</b> <?= count($ccir->id_comisiones) ?><br>

<b>Comisiones mezcladas:</b>
<?= ($ccir->comisiones_mezcladas) ? "Si" : "No" ?><br>

<b>Comisiones diferente plan:</b>
<?= ($ccir->comisiones_diferente_plan) ? "Si" : "No" ?>

</div>


<table class="rdd2-table">

<thead>
<tr>

<th>Alumno</th>

<?php foreach ($disposicionesPorId as $dispId => $disp): ?>

<?php
$semClass = ($disp->planificacion_->semestre == 1) ? "sem1" : "sem2";
?>

<th class="<?= $semClass ?>">
<?= htmlspecialchars($disp->getLabel()) ?>
</th>

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

<?php
$nota = $tabla[$alumnoId][$dispId] ?? "";
$class = $nota ? "ok" : "missing";
?>

<td class="<?= $class ?>" style="text-align:center">
<?= $nota ?>
</td>

<?php endforeach; ?>

</tr>

<?php endforeach; ?>

</tbody>

</table>