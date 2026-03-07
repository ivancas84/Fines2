<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

add_action('admin_post_ap3_comisiones_update', 'ap3_comisiones_update_handle');

use Fines2\AlumnoComision_;
use Fines2\Calificacion_;
use \SqlOrganize\Utils\ValueTypesUtils;

function ap3_comisiones_update_handle() {

    try {
        $persona_id = wp_initialize_handle("fines-plugin-ap3", "ap3_comisiones_update_handle", "persona_id");
  
        $i = 0;
        $countActualizados = 0;

        while (isset($_POST["comision_id$i"])) {
            $acData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

            $ac = new AlumnoComision_();
            $ac->initById($acData["comision_id"]);
            $ac->ssetFromArray($acData);
            if($ac->_status < 1){
                $ac->update();
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