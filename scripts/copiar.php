<?php
echo __FILE__;

require_once 'db_config.php';

// Connect to the database
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Updated SQL query to include file.id and file.name
$query = "
    SELECT persona.numero_documento, persona.apellidos, persona.nombres, file.content, file.id as file_id, file.name as file_name 
    FROM persona 
    INNER JOIN detalle_persona ON detalle_persona.persona = persona.id 
    INNER JOIN file ON detalle_persona.archivo = file.id;
";

// Execute the query and get the result
$result = $mysqli->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Define the upload directory on the server
$upload_dir = '/home/planfi10/domains/planfines2.com.ar/public_html/upload';  // Modify with the actual path to the upload directory

// Define the destination directory where you want to save the downloaded files
$destination_dir = '/home/planfi10/domains/planfines2.com.ar/public_html/upload2';  // Modify with the actual path

// Ensure the destination directory exists, create it if not
if (!file_exists($destination_dir)) {
    mkdir($destination_dir, 0777, true);
}

// Iterate through the query result and download the files
while ($row = $result->fetch_assoc()) {
    // Get the file path relative to the "upload/" directory
    $relative_file_path = $row['content'];
    
    // Construct the absolute path to the file on the server
    $absolute_file_path = $upload_dir . '/' . $relative_file_path;

    // Check if the file exists
    if (file_exists($absolute_file_path)) {
        // Define the new directory structure
        $new_dir = $destination_dir . '/' . $row['numero_documento'] . " " . $row["apellidos"] . " " . $row["nombres"];
        
        // Ensure the new directory exists, create it if not
        if (!file_exists($new_dir)) {
            mkdir($new_dir, 0777, true);  // Create directories with appropriate permissions
        }
        
        // Define the new file path in the new directory structure
        $new_file_path = $new_dir . '/' . basename($row['file_id'] . '_' . $row['file_name']);  // Copy with original filename
        
        // Copy the file to the new directory structure
        if (!copy($absolute_file_path, $new_file_path)) {
            echo "Failed to copy file: " . $relative_file_path . "<br>";
        } else {
            echo "Successfully copied: " . $relative_file_path . " to " . $new_file_path . "<br>";
        }
    } else {
        echo "File not found: " . $relative_file_path . "<br>";
    }
}

// Close the database connection
$mysqli->close();

?>
