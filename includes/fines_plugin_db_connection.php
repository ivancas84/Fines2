<?php
function fines_plugin_db_connection() {
    global $wpdb;

    // Custom database credentials
    $db_host = 'localhost';        // e.g., 'localhost'
    $db_name = 'planfi10_20204';    // Name of the external database
    $db_user = 'planfi10_2020';    // Database username
    $db_pass = 'AkDNaVQc8YeGX8ZkUWen';    // Database password

    // Establish a connection
    $custom_db = new wpdb($db_user, $db_pass, $db_name, $db_host);

    if ($custom_db->dbh) {
        return $custom_db;
    } else {
        return false; // Connection failed
    }
}
?>