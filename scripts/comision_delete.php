<?php



require_once __DIR__ . '/db-config.php';

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

try {
    $comision_id = $_POST["comision_id"];
    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();
    $modifyQueries = $db->CreateModifyQueries();
    $cursos = $dataProvider->fetchAllEntitiesByParams("curso",["comision" => $comision_id]);
    $idsCursos = ValueTypesUtils::arrayOfName($cursos, "id");
    $modifyQueries->buildDeleteSqlByIds("curso", ...$idsCursos);
    $modifyQueries->buildDeleteSqlById("comision", $comision_id);
    echo "<pre>";
    echo $modifyQueries->getSql();
    print_r($modifyQueries->parameters);
    echo "</pre>";

    $modifyQueries->process();

    echo "<p>Finalizado correctamente, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}