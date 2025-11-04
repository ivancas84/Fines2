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

    public static function searchPersonas($search): array{
         $sql = "
            SELECT persona.id 
            FROM persona 
            WHERE lower(apellidos)  LIKE lower(:search) 
            OR lower(nombres) LIKE lower(:search)
            OR lower(numero_documento) LIKE lower(:search)
            OR lower(telefono) LIKE lower(:search)
            OR lower(email) LIKE lower(:search)
            OR lower(email_abc) LIKE lower(:search)";

        /** @var DataProvider */ $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
        return $dataProvider->fetchAllEntitiesBySqlId("persona", $sql, ['search' => '%' . $search . '%']);
    }

}