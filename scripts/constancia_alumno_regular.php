<?php
require_once 'db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded


$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function constancia_alumno_regular() {
    global $pdo;

    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $numero_documento = $_POST["numero_documento"];
	
	$anio_en_curso = $_POST["anio_en_curso"];
	$orientacion = $_POST["orientacion"];
	$resolucion = $_POST["resolucion"];

	$fecha = $_POST["fecha"];
    $presentado = $_POST["presentado"];
    $observaciones = $_POST["observaciones"];;
	

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
    $content = "Se certifica que el estudiante " . $nombres . 
               ", con documento número " . $numero_documento . 
               ", está inscrito en el año " . $anio_en_curso . ".";
    
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
