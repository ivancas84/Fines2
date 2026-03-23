<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Curso_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    //si el campo delete_index esta definido se realizara la eliminación
    if(!empty($_POST["delete_index"])){
        $i = $_POST["delete_index"];
        $curso_id = $_POST["curso_id" . $i];
        $modifyQueries->deleteSqlById("curso", $curso_id);
        $modifyQueries->execute();
        ValueTypesUtils::redirect("Curso Eliminado");
        exit;
    } 

    $i = 0;

    while (isset($_POST["curso_id$i"])) {
        $cursoData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

        /** @var Curso_ */ $curso = $db->createEntityById("curso", $cursoData["curso_id"]);
        $curso->setFromArray($cursoData);
        $curso->update($modifyQueries);
        $i++;
    }

    $modifyQueries->process();
    ValueTypesUtils::redirect("Cursos modificados");

} catch (Exception $ex){
    echo $ex->getMessage();
    ValueTypesUtils::redirect($ex->getMessage(), 3);
}