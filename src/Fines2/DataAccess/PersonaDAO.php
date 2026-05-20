<?php

namespace Fines2\DataAccess;

use App\Context;
use DateTime;
use Exception;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\Entity;

class PersonaDAO
{

    public static function createPersonaByUnique(array $param): Entity {
        /** @var Db */ $db = Context::getFinesDb();

        /** @var ?Persona */ $obj = $db->createDataProvider()->fetchEntityByUnique("persona", $param);
        if ($obj) {
            if(!Persona_::nombreParecido($obj->toArray(), $param)) throw new Exception("Los nombres no son parecidos al registro almacenado");
            $obj->_status = 1;
            $obj->_changeLog = [];
        } else {
            $obj = $db->createEntity("persona");
            $obj->_status = -1;
        }

        $obj->ssetNotNull($param);

        if(!isset($param["nacionalidad"]) && empty($obj->get("nacionalidad"))){
            $obj->set("nacionalidad", "Argentina");
        }
        
        if (isset($param["genero"]) && !empty($param["genero"])) {
            $genero = strtolower(trim($param["genero"]));
            $inicial = substr($genero, 0, 1);

            if ($inicial === 'm') {
                $obj->set("genero", "Masculino");
                $obj->set("sexo", 1);
            } else {
                $obj->set("genero", "Femenino");
                $obj->set("sexo", 2);
            }
        }

        /** @var DateTime|null $fecha_nacimiento */
        $fecha_nacimiento = $obj->get("fecha_nacimiento");

        if ($fecha_nacimiento instanceof DateTime) {
            
            $obj->set("dia_nacimiento", (int) $fecha_nacimiento->format('d'));
            $obj->set("mes_nacimiento", (int) $fecha_nacimiento->format('m'));
            $obj->set("anio_nacimiento", (int) $fecha_nacimiento->format('Y'));
            
        }


        $cuil = $obj->get("cuil");
        if (!empty($cuil) && strlen($cuil) == 11) {
                $cuil1 = substr($cuil, 0, 2);   // primeros 2 dígitos
                $cuil2 = substr($cuil, -1);     // último dígito

                $obj->set("cuil1", $cuil1);
                $obj->set("cuil2", $cuil2);
        }
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