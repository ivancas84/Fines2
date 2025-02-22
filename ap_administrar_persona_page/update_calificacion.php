<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $field = $_POST["field"];
    $value = $_POST["value"];

    // Validate allowed fields
    if (!in_array($field, ["nota_final", "crec"])) {
        die("Invalid field");
    }

    $wpdb = fines_plugin_db_connect();

    // Update the database
    $wpdb->update(
        "calificacion",
        [$field => $value],
        ["id" => $id]
    );

    echo "Success";
}
?>
