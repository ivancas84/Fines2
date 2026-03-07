<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Alumno_;
use \Fines2\Persona_;

add_action('admin_post_ap3_alumno_admin', 'ap3_alumno_admin_handle');

function ap3_alumno_admin_handle() {
    $persona_id = wp_initialize_handle("fines-plugin-ap3", "ap3_alumno_admin", "persona_id");

    $_POST["tiene_certificado"] = array_key_exists("tiene_certificado", $_POST) ? $_POST["tiene_certificado"] : false;
    $_POST["tiene_constancia"] = array_key_exists("tiene_constancia", $_POST) ? $_POST["tiene_constancia"] : false;
    $_POST["tiene_dni"] = array_key_exists("tiene_dni", $_POST) ? $_POST["tiene_dni"] : false;
    $_POST["tiene_partida"] = array_key_exists("tiene_partida", $_POST) ? $_POST["tiene_partida"] : false;
    $_POST["confirmado_direccion"] = array_key_exists("confirmado_direccion", $_POST) ? $_POST["confirmado_direccion"] : false;
    $_POST["previas_completas"] = array_key_exists("previas_completas", $_POST) ? $_POST["previas_completas"] : false;
    
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