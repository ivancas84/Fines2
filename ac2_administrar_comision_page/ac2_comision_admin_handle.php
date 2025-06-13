<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

add_action('admin_post_ac2_comision_admin', 'ac2_comision_admin_handle');

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\Comision_;

function ac2_comision_admin_handle() {

    $comision_id = initialize_handle("fines-plugin-ac2", "ac2_comision_admin", "comision_id");
    $comision = new Comision_();
    $comision->ssetFromArray($_POST);
    $comision->reset();

    if(!$comision->check()) {
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $comision->getLogging()->__toString());
        exit;
    }

    $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
    $modifyQueries->buildPersistSql($comision);
    $modifyQueries->process();
    wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Registro realizado");

    /*
    // Insertar curso si no existe
    $disposiciones = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, asignatura, horas_catedra FROM disposicion WHERE planificacion = %s",
            $planificacion_id
        ),
        ARRAY_A
    );
    
    foreach ($disposiciones as $disposicion) {
        $curso_existente = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM curso WHERE comision = %s AND disposicion = %s",
                $comision_id,
                $disposicion['id']
            )
        );

        if ($curso_existente == 0) {
            $curso_id = uniqid();
            $insert_result = $wpdb->insert(
                'curso',
                [
                    'id' => $curso_id,
                    'horas_catedra' => $disposicion['horas_catedra'],
                    'comision' => $comision_id,
                    'disposicion' => $disposicion['id'],
                    'asignatura' => $disposicion['asignatura'],
                ],
                ['%s', '%d', '%s', '%s', '%s']
            );
            if(!$insert_result) {
                echo json_encode(['success' => false, 'message' => 'Error al crear cursos: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'comision_id' => $comision_id ]);
    die();*/
}
