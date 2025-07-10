<?php

//Modificar el valor de toma.estado_planilla con el valor "Entregada"

require_once '../db-config.php';

use SqlOrganize\Sql\DbMy;

try {
    $toma_id = $_GET["toma_id"];
    $estado = $_GET["estado"] ?? "entregada";
    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();
    $modifyQueries = $db->CreateModifyQueries();
    $modifyQueries->buildUpdateKeyValueSqlById("toma", "estado_planilla", $estado, $toma_id);
    $modifyQueries->execute();

    echo "<p>Estado modificado correctamente, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}