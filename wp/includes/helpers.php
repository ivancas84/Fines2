<?php


function wp_page_message(){
    $message = !empty($_REQUEST['message']) ? $_REQUEST['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
}

function boolToSiNo($value) {
    return $value ? 'Si' : 'No';
}

/**
 * Declarar formulario sin ajax
 * 
 * @example
 * <?php wp_html_init_form("ac2_cursos_admin", "comision_id", $comision->id); ?>
 */
function wp_html_init_form($handleName, $fieldIdName, $fieldIdValue){

    wp_nonce_field($handleName . '_action', $handleName . '_nonce'); 
    echo '<input type="text" name="honeypot" style="display: none;">
    <input type="hidden" name="action" value="' . $handleName . '">
    <input type="hidden" name="' . $fieldIdName . '" value="' . esc_attr($fieldIdValue) . '">';    
}

function wp_initialize_handle($page_name, $handle_name, $field_id, $wp_verify_nonce = false){
    $field_value = $_REQUEST[$field_id];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=No tienes permisos suficientes"));
        exit;
    }

    if ($wp_verify_nonce && (!isset($_POST[$handle_name . '_nonce']) || !wp_verify_nonce($_POST[$handle_name . '_nonce'], $handle_name . '_action'))) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=Detección de spam"));
        exit;
    }

    return $field_value;
}

function wp_redirect_handle($page_name, $field_id, $field_value, $message = ""){
    wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=$message"));
}