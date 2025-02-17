<?php
echo __FILE__;

require_once 'db_config.php'; // Database credentials (ignored in Git)

// Connect to the database
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get parameter from request safely
$pfid = $_GET['comision_pfid'] ?? "";

// Define query based on whether $pfid is provided
if (empty($pfid)) {
    $query = "
        SELECT persona.numero_documento, persona.apellidos, persona.nombres, 
               file.content, file.id as file_id, file.name as file_name 
        FROM persona 
        INNER JOIN detalle_persona ON detalle_persona.persona = persona.id 
        INNER JOIN file ON detalle_persona.archivo = file.id
    ";

    // Execute a simple query (no need for prepare & bind_param)
    $result = $mysqli->query($query);
} else {
    $query = "
        SELECT DISTINCT 
            persona.id AS persona_id, 
            persona.nombres, 
            persona.apellidos, 
            persona.numero_documento, 
            file.content, 
            file.id as file_id, 
            file.name as file_name 
        FROM alumno
        INNER JOIN persona ON alumno.persona = persona.id
        INNER JOIN detalle_persona ON detalle_persona.persona = persona.id 
        INNER JOIN file ON detalle_persona.archivo = file.id
        INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
        WHERE alumno.id IN (
            SELECT DISTINCT alumno 
            FROM alumno_comision 
            INNER JOIN comision ON comision.id = alumno_comision.comision
            WHERE comision.pfid = ?
        )
    ";

    // Prepare and execute the statement
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $mysqli->error);
    }

    // Bind parameter and execute
    $stmt->bind_param("s", $pfid);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Define directories
$upload_dir = '/home/planfi10/domains/planfines2.com.ar/public_html/upload';
$destination_dir = '/home/planfi10/domains/planfines2.com.ar/public_html/upload2';

// Ensure destination directory exists
if (!file_exists($destination_dir)) {
    mkdir($destination_dir, 0777, true);
}

// Iterate through query results
while ($row = $result->fetch_assoc()) {
    $relative_file_path = $row['content'];
    $absolute_file_path = $upload_dir . '/' . $relative_file_path;

    // Check if file exists
    if (file_exists($absolute_file_path)) {
        // Create sub-directory with document ID, surname, and name
        $new_dir = $destination_dir . '/' . $row['numero_documento'] . " " . $row["apellidos"] . " " . $row["nombres"];

        if (!file_exists($new_dir)) {
            mkdir($new_dir, 0777, true);
        }

        // Define the new file path
        $new_file_path = $new_dir . '/' . basename($row['file_id'] . '_' . $row['file_name']);

        // Copy the file
        if (!copy($absolute_file_path, $new_file_path)) {
            echo "Failed to copy file: " . htmlspecialchars($relative_file_path) . "<br>";
        } else {
            echo "Successfully copied: " . htmlspecialchars($relative_file_path) . " to " . htmlspecialchars($new_file_path) . "<br>";
        }
    } else {
        echo "File not found: " . htmlspecialchars($relative_file_path) . "<br>";
    }
}

// Close statement (if used)
if (!empty($pfid)) {
    $stmt->close();
}

// Close database connection
$mysqli->close();
?>
