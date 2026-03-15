<?php

namespace Fines2\DataAccess;

use App\Context;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use Exception;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\Entity;

class PersonaDAO
{

    public static function createPersonaByUnique(array $param): Entity {
        /** @var Db */ $db = Context::getFinesDb();

        $obj = $db->createDataProvider()->fetchEntityByUnique("persona", $param);
        if ($obj) {
            if(!Persona_::nombreParecido($obj->toArray(), $param)) throw new Exception("Los nombres no son parecidos al registro almacenado");
            $obj->_status = 1;
            $obj->_changeLog = [];
        } else {
            $obj = $db->createEntity("persona");
            $obj->_status = -1;
        }
        $obj->ssetFromArray($param);
        return $obj;
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