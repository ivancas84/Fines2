<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Alumno_;
use \Fines2\Persona_;

add_action('admin_post_ap3_alumno_admin', 'ap3_alumno_admin_handle');

function ap3_alumno_admin_handle() {
    $persona_id = wp_initialize_handle("fines-plugin-ap3", "ap3_alumno_admin", "persona_id");

    try {
        $_POST["id"] = $_POST["alumno_id"];
        $_POST["persona"] = $persona_id;
        $alumno = new Alumno_();
        $alumno->initByUnique($_POST);
        $alumno->ssetFromArray($_POST);
        $alumno->reset();
        if(!$alumno->check())
            throw new Exception($alumno->getLogging()->__toString());

        if($alumno->_status == 0) {
            $alumno->update();
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, "Registro actualizado");  
        } elseif($alumno->_status < 0) {
            $alumno->insert();
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, "Registro insertado");  
        } else {
            wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, "Sin modificaciones");  
        }

    } catch(Exception $ex){
        wp_redirect_handle("fines-plugin-ap3", "persona_id", $persona_id, $ex->getMessage());
    }

}