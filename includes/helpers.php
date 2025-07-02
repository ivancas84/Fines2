<?php

function html_hidden_inputs($values, $fieldNames) {
    foreach($fieldNames as $fieldName) {
        echo "<input type='hidden' name='$fieldName' value='" . esc_attr($values[$fieldName]) . "' />";
    }
}

function html_select($fieldName, $options, $values){
    echo "<select name='$fieldName'>
            <option value=''>-- Seleccione --</option>";
    foreach ($options as $option) {
        $selected = (isset($values[$fieldName]) && $values[$fieldName] === $option) ? 'selected' : '';
        echo "<option value='" . esc_attr($option) . "' $selected>" . esc_html($option) . "</option>";
    }
    echo "</select>";
}

function wp_page_message(){
    $message = !empty($_REQUEST['message']) ? $_REQUEST['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
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

function wp_initialize_handle($page_name, $handle_name, $field_id){
    $field_value = $_REQUEST[$field_id];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=No tienes permisos suficientes"));
        exit;
    }

    /*if (!isset($_POST[$handle_name . '_nonce']) || !wp_verify_nonce($_POST[$handle_name . '_nonce'], $handle_name . '_action')) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=Error de seguridad"));
        exit;
    }*/

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=Detección de spam"));
        exit;
    }

    return $field_value;
}

function wp_redirect_handle($page_name, $field_id, $field_value, $message = ""){
    wp_redirect(admin_url("admin.php?page=$page_name&$field_id=$field_value&message=$message"));
}

function sanitize_or_null_text_field($value) {
    $value = sanitize_text_field($value);
    return $value === '' ? null : $value;
}

function sanitize_or_null_text_field_from_array(&$array, $fieldName) {
    $array[$fieldName] = sanitize_text_field($array[$fieldName]);
    $array[$fieldName] = empty($array[$fieldName]) ? null : $array[$fieldName];
}

function sanitize_or_null_email_field_from_array(&$array, $fieldName) {
    $array[$fieldName] = sanitilize_email($array[$fieldName]);
    $array[$fieldName] = empty($array[$fieldName]) ? null : $array[$fieldName];
}

function intval_or_null_field_from_array(&$array, $fieldName) {
    if (isset($array[$fieldName]) && is_numeric($array[$fieldName])) {
        $array[$fieldName] = intval($array[$fieldName]);
    } else {
        $array[$fieldName] = null;
    }
}

function boolval_field_from_array(&$array, $fieldName) {
    if (isset($array[$fieldName]) && Tools::toBool($array[$fieldName])) {
        $array[$fieldName] = boolval($array[$fieldName]);
    } else {
        $array[$fieldName] = false;
    }
}

function boolval_fields_from_array(&$array, $fieldNames) {
    foreach ($fieldNames as $fieldName) {
        boolval_field_from_array($array, $fieldName);
    }
}

function intval_or_null_fields_from_array(&$array, $fieldNames) {
    foreach ($fieldNames as $fieldName) {
        intval_or_null_field_from_array($array, $fieldName);
    }
}

function sanitize_or_null_text_fields_from_array(&$array, $fieldNames) {
    foreach ($fieldNames as $fieldName) {
        sanitize_or_null_text_field_from_array($array, $fieldName);
    }
}

function sanitize_or_null_email_fields_from_array(&$array, $fieldNames) {
    foreach ($fieldNames as $fieldName) {
        sanitize_or_null_text_field_from_array($array, $fieldName);
    }
}

function toTitleCase($string) {
    return ucwords(strtolower($string));
}

function toOrdinalSpanish($number) {
    $ordinals = [1 => "PRIMERO", 2 => "SEGUNDO", 3 => "TERCERO", 4 => "CUARTO", 5 => "QUINTO"];
    return $ordinals[$number] ?? $number . "°";
}

function isNoE($string) {
    return empty(trim($string));
}

function getAcronym($string) {
    $ignoreWords = ['de', 'y', 'la', 'el', 'los', 'las', 'en', 'del', 'al']; // Words to ignore
    $words = explode(' ', $string);
    $acronym = '';

    foreach ($words as $word) {
        $word = strtolower(trim($word)); // Normalize case and trim spaces
        if (!in_array($word, $ignoreWords) && !empty($word)) {
            $acronym .= strtoupper($word[0]); // Get first letter
        }
    }

    return $acronym;
}

function boolToSiNo($value) {
    return $value ? 'Si' : 'No';
}

function tramoSiguiente($tramo): string{
    switch($tramo){
        case "11":
            return "12";
            break;
        case "12":
            return "21";
            break;
        case "21":
            return "22";
            break;
        case "22":
            return "31";
            break;
        case "31":
            return "32";
            break;

    }
}

function mes($numero_mes){
    $meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    return $meses[$numero_mes - 1];
}


function fechaActualDiaDeMesDeAnio(){
    $fecha = new DateTime();
    $dia = $fecha->format('d');
    $mes = mes($fecha->format('n')); // Obtener el mes en español
    $anio = $fecha->format('Y');

    return "$dia de $mes de $anio";
}

?>