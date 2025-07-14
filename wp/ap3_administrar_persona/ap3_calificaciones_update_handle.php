<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

add_action('admin_post_ap3_calificaciones_update', 'ap3_calificaciones_update_handle');

use Fines2\Calificacion_;
use \SqlOrganize\Utils\ValueTypesUtils;

function ap3_calificaciones_update_handle() {

    try {
        $persona_id = wp_initialize_handle("fines-plugin-ap3", "ap3_calificaciones_update", "persona_id");
  
        $i = 0;
        $countActualizados = 0;

        while (isset($_POST["calificacion_id$i"])) {
            $calificacionData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

            $calificacion = new Calificacion_();
            $calificacion->initById($calificacionData["calificacion_id"]);
            $calificacion->ssetFromArray($calificacionData);
            if($calificacion->_status < 1){
                $calificacion->update();
                $countActualizados++;
            }
            $i++;
        }

        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, $countActualizados . " registros actualizados");

    
    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, $ex->getMessage());
        exit;
    }

  
}