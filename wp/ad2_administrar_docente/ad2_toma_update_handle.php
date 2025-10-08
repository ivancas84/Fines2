<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use \Fines2\Persona_;

add_action('admin_post_ad2_toma_update', 'ad2_toma_update_handle');

function ad2_toma_update_handle() {
    $persona_id = wp_initialize_handle("fines-plugin-ad2", "ad2_toma_update", "persona_id");

    try {
        $_POST["id"] = $persona_id;
        $db = \App\Context::getFinesDb();

        /**  @var ?Persona_ */ $persona = $db->createDataProvider()->fetchEntityByUnique("persona", $_POST);
        if(!$persona) {
            $persona = new Persona_();
            $persona->_status = -1;
        }
        $persona->ssetFromArray($_POST);
        $persona->reset();
        if(!$persona->check())
            throw new Exception($persona->getLogging()->__toString());

        if($persona->_status == 0) {
            $persona->update();
            wp_redirect_handle("fines-plugin-ad2", "persona_id", $persona->id, "Registro actualizado");  
        } elseif($persona->_status < 0) {
            $persona->insert();
            wp_redirect_handle("fines-plugin-ad2", "persona_id", $persona->id, "Registro insertado");  
        } else {
            wp_redirect_handle("fines-plugin-ad2", "persona_id", $persona->id, "Sin modificaciones");  
        }

    } catch(Exception $ex){
        wp_redirect_handle("fines-plugin-ad2", "persona_id", $persona_id, $ex->getMessage());
    }

}