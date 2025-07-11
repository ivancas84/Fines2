<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use \SqlOrganize\Sql\Entity;
use \Fines2\Persona_;

add_action('admin_post_ap3_persona_admin', 'ap3_persona_admin_handle');

function ap3_persona_admin_handle() {
    try {
        $_POST["id"] = wp_initialize_handle("fines-plugin-ap3", "ap2_persona_admin", "persona_id");

        $persona = new Persona_();
        $persona->initByUnique($_POST);
        $persona->ssetFromArray($_POST);
        $persona->reset();
        if(!$persona->check())
            throw new Exception($persona->getLogging()->__toString());

        $persona->persistByStatus();
        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona->id, "Registro realizado");  
    } catch(Exception $ex){
        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona->id, $ex->getMessage());
    }

}