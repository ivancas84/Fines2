<?php
add_action('wp_ajax_update_calificacion_handle', 'update_calificacion_handle');
add_action('wp_ajax_nopriv_update_calificacion_handle', 'update_calificacion_handle');

function update_calificacion_handle() {
    // Security check
    if (!isset($_POST['id']) || !isset($_POST['field']) || !isset($_POST['value'])) {
        wp_send_json_error(["message" => "Missing parameters"]);
        exit;
    }


    $wpdb = fines_plugin_db_connect();
    $id = sanitize_text_field($_POST['id']);
    $field = sanitize_text_field($_POST['field']);
    $value = floatval($_POST['value']);

    // Allow only valid fields
    $allowed_fields = ['nota_final', 'crec'];
    if (!in_array($field, $allowed_fields)) {
        wp_send_json_error(["message" => "Invalid field"]);
        exit;
    }

    // Update the database
    $updated = $wpdb->update(
        "calificacion",
        [$field => $value],
        ["id" => $id],
        ['%f'],  // Value format
        ['%s']   // ID format
    );

    if ($updated !== false) {
        wp_send_json_success(["message" => "Record updated successfully"]);
    } else {
        wp_send_json_error([
            "message" => "Failed to update",
            "error" => $wpdb->last_error  // Return the last database error
        ]);    
    }

    exit;
}
