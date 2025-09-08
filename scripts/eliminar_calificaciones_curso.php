<?php

//Eliminar todas las calificaciones del curso

require_once '../fines-config.php';

use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;

try {
    $curso_id = $_GET["curso_id"];
    $db = \App\Context::getFinesDb();
    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
    $ids = $dataProvider->fetchAllColumnByParams("calificacion", "id", ["curso" => $curso_id]);
    $modifyQueries->buildDeleteSqlByIds("calificacion", ...$ids);
    $modifyQueries->execute();

    echo "<p>Calificaciones eliminadas, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}