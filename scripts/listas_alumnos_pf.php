<?php

require_once 'class/ProgramaFines.php';
require_once 'class/PdoFines.php';

require_once 'includes/db_config.php';

$calendario_id = "202502110007";


$pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
$programaFines = new ProgramaFines(PF_USER, PF_PASSWORD);

$pfids = $pdoFines->pfidsComisionesByCalendario($calendario_id);

foreach($pfids as $pfid) {
    $alumnosPf = $programaFines->infoListaAlumnos($pfid);

    if(count($alumnosPf) <= 0)
        continue;

    echo "<h1>Alumnos PF comision " . $pfid . "</h1>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>
    <th>Nombre</th>
    <th>DNI</th>
    <th>Fecha de Nacimiento</th>
    <th>Teléfono</th>
    <th>Email</th>
    </tr>";

    foreach($alumnosPf as $alumnoPf){
        
        
            echo "<tr>";
            echo "<td>" . htmlspecialchars($alumnoPf['nombres']) . "</td>";
            echo "<td>" . htmlspecialchars($alumnoPf['dni']) . "</td>";
            echo "<td>" . htmlspecialchars($alumnoPf['fecha_nacimiento']) . "</td>";
            echo "<td>" . htmlspecialchars($alumnoPf['telefono']) . "</td>";
            echo "<td>" . htmlspecialchars($alumnoPf['email']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
}
echo "<br><br>";
    
