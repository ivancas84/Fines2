<?php

//Definir sql para transferir alumnos aprobados

require_once '../fines-config.php';

use Fines2\Calificacion_;
use Fines2\Comision_;
use SqlOrganize\Sql\DbMy;

try {
    
    /** @var Comision_[] */ $comisionesSemestreAnterior = Fines2\ComisionDAO::comisionesConSiguienteSin32ByCalendario(CALENDARIO_ID_ANTERIOR);
    
    /** @var Calificacion_[] */ $comisionesSemestreAnterior = Fines2\ComisionDAO::comisionesConSiguienteSin32ByCalendario(CALENDARIO_ID_ANTERIOR);
    echo "<h3>Comisiones a procesar: " . count($comisionesSemestreAnterior) . "</h3>";
    
    foreach($comisiones as $comision){
        echo "<h2>Procesando " . $comision->pfid . "</h2>";



    }   





    

    //$dataProvider = $db->CreateDataProvider();
    //$modifyQueries = $db->CreateModifyQueries();

    //echo "<p>Estado modificado correctamente, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}