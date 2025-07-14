<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use \Fines2\Persona_;

add_action('admin_post_ap3_persona_admin', 'ap3_persona_admin_handle');

function ap3_persona_admin_handle() {
    $persona_id = wp_initialize_handle("fines-plugin-ap3", "ap3_persona_admin", "persona_id");

    try {
        $_POST["id"] = $persona_id;
        $persona = new Persona_();
        $persona->initByUnique($_POST);
        $persona->ssetFromArray($_POST);
        $persona->reset();
        if(!$persona->check())
            throw new Exception($persona->getLogging()->__toString());

        if($persona->_status == 0) {
            $persona->update();
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona->id, "Registro actualizado");  
        } elseif($persona->_status < 0) {
            $persona->insert();
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona->id, "Registro insertado");  
        } else {
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona->id, "Sin modificaciones");  
        }

    } catch(Exception $ex){
        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, $ex->getMessage());
    }

}