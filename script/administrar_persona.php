<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    /** @var ModifyQueries */ $modifyQueries = Context::getFinesDb()->CreateModifyQueries();
    $_POST["id"] = $persona_id;
    
    $persona = new Persona_();
    $persona->initByUnique($_POST);
    $persona->ssetFromArray($_POST);
    $persona->reset();

    if(!$persona->check())
        throw new Exception($persona->getLogging()->__toString());

    if($persona->_status == 0) {
        $persona->update($modifyQueries);
        $message = "Registro actualizado";  

    } elseif($persona->_status < 0) {
        $persona->insert($modifyQueries);
        $message = "Registro insertado";  

    } else {
        $message = "Sin modificaciones";  
    }

    ValueTypesUtils::redirect($message);

} catch (Exception $ex){
    echo $ex->getMessage();
    ValueTypesUtils::redirect($ex->getMessage());
}

