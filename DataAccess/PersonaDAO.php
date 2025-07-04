<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use Exception;

class PersonaDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, array $data): Persona_{
        $persona = new Persona_();
        $persona->initByUnique($data);
        if ($persona->_status === 0){
            if(!Persona_::nombreParecido($persona->toArray(), $data))
                throw new Exception("El nombre registrado de la persona es diferente " . $persona->getLabel());
            $modifyQueries->buildUpdateSql($persona);
        }
        else if ($persona->_status < 0)
            $modifyQueries->buildInsertSql($persona);

        return $persona;
    }
}