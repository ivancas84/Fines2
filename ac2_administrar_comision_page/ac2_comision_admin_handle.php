<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

add_action('admin_post_ac2_comision_admin', 'ac2_comision_admin_handle');

use \SqlOrganize\Sql\DbMy;
use \Fines2\Comision_;
use \Fines2\Curso_;
use \SqlOrganize\Utils\ValueTypesUtils;

function ac2_comision_admin_handle() {

    try {
        $db = DbMy::getInstance();
        
        $comision_id = wp_initialize_handle("fines-plugin-ac2", "ac2_comision_admin", "comision_id");
        $comision = new Comision_();
        $comision->ssetFromArray($_POST);
        $comision->id = $comision_id;
        $comision->reset();

        if(!$comision->check()) {
            wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $comision->getLogging()->__toString());
            exit;
        }

        $modifyQueries = $db->CreateModifyQueries();
        $modifyQueries->buildPersistSql($comision);

        $dataProvider = $db->CreateDataProvider();

        $disposiciones = $dataProvider->fetchAllEntitiesByParams("disposicion", ["planificacion" => $comision->planificacion]);
        $idDisposiciones = ValueTypesUtils::arrayOfName($disposiciones, "id");
        $cursosExistentes = $dataProvider->fetchAllEntitiesByParams("curso", ["comision" => $comision->id, "disposicion" => $idDisposiciones]);
        $cursosExistentes = ValueTypesUtils::dictOfObjByPropertyNames($cursosExistentes, "disposicion");

        $i = 0;
        foreach($disposiciones as $disposicion){
            if(array_key_exists($disposicion->id, $cursosExistentes))
                continue;

            $i++;
            $curso = new Curso_();
            $curso->horas_catedra = $disposicion->horas_catedra;
            $curso->disposicion = $disposicion->id;
            $curso->asignatura = $disposicion->asignatura;
            $modifyQueries->buildInsertSql($curso);
        }

        $modifyQueries->process();

        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Comision y $i cursos registrados");
    } catch (Exception $ex){
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $ex->getMessage());
    }
}
