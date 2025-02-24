<?php
require_once 'db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded


$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function constancia_alumno_regular() {
    global $pdo;


    $idAlumno = $_GET['id_alumno'] ?? throw new Exception("No se especificó el ID del alumno.");

    $persona = wpdbPersona__By_id($wpdb, $persona_id);


    // Fetch student data
    $stmt = $pdo->prepare("SELECT p.nombres, p.numero_documento, a.anio_inscripcion 
                           FROM persona p 
                           JOIN alumno a ON p.id = a.persona 
                           WHERE a.id = ?");
    $stmt->execute([$idAlumno]);
    $alumno = $stmt->fetch();

    if (!$alumno) {
        throw new Exception("No se encontró el alumno con ID $idAlumno.");
    }

    // Create PDF instance
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Institución');
    $pdf->SetTitle('Constancia de Alumno Regular');
    $pdf->SetMargins(15, 20, 15);
    $pdf->AddPage();

    // Title
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, "Constancia de Alumno Regular", 0, 1, 'C');
    $pdf->Ln(10);

    // Content
    $pdf->SetFont('helvetica', '', 12);
    $content = "Se certifica que el estudiante " . $alumno['nombres'] . 
               ", con documento número " . $alumno['numero_documento'] . 
               ", está inscrito en el año " . $alumno['anio_inscripcion'] . ".";
    
    $pdf->MultiCell(0, 10, $content, 0, 'L');

    // Output PDF
    $pdf->Output("Constancia_Alumno_Regular.pdf", "I"); // Display in browser
}

// Call the function
try {
    constancia_alumno_regular();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
