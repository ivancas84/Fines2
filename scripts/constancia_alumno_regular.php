<?php
require_once 'db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function constancia_alumno_regular() {
    global $pdo;

    $apellidos = strtoupper($_POST["apellidos"]);
    $nombres = ucwords(strtolower($_POST["nombres"]));
    $numero_documento = $_POST["numero_documento"];
    $anio = $_POST["anio_en_curso"];
    $resolucion = $_POST["resolucion"];
    $fecha = $_POST["fecha"];
    $presentacion = $_POST["presentado"];
    $orientacion = $_POST["orientacion"];
    $observaciones = $_POST["observaciones"];
    
    // Create QR Code
    $qrContent = "Validación: $apellidos, $nombres - DNI: $numero_documento";

    $options = new QROptions([
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'scale' => 5,
    ]);

    $qrcode = (new QRCode($options))->render($qrContent);

    // Save the QR Code as a temporary file
    $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

    // Create PDF instance
    $pdf = new TCPDF('L', 'mm', 'A5'); // 'L' for Landscape, 'A5' for A5 paper size
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Escuela CENS Nº 462');
    $pdf->SetTitle('Constancia de Alumno Regular');
    $pdf->SetMargins(20, 30, 20);
    $pdf->setPrintHeader(false); // Avoid header line
    $pdf->AddPage();
    $pdf->Rect(10, 10, 190, 125); // Full-page border

    // Header with logo and QR code
    $pdf->Image('./images/logo.jpg', 20, 15, 120, 0, 'JPG'); // Logo occupies 2/3
    $pdf->Image($qrFile, 160, 15, 30, 30, 'PNG'); // QR occupies 1/3
    $pdf->Ln(15);
    $pdf->SetAlpha(0.9);

    // Add images at the bottom
    $pdf->Image('./images/sello_cens.png', 85, 85, 30, 40, 'PNG'); // Bottom Center
    $pdf->Image('./images/firma_director_luis.png', 120, 90, 60, 35, 'PNG'); // Bottom Right

    $pdf->SetAlpha(1); // Reset transparency
    // Title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, "CONSTANCIA DE ALUMNO REGULAR", 0, 1, 'C');
    $pdf->Ln(5);

    // Justify the content with interlineado
    $pdf->SetFont('helvetica', '', 10);
    $content = "
    <p >La Dirección de la Escuela de Educación CENS Nº 462 de La Plata, hace constar por la presente que
    <strong><u><i>&nbsp;&nbsp;&nbsp;{$apellidos}, {$nombres}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$numero_documento}&nbsp;&nbsp;&nbsp;</i></u></strong> 
    es alumno/a regular de <strong><u><i>&nbsp;&nbsp;&nbsp;{$anio}&nbsp;&nbsp;&nbsp;</i></u></strong> año <strong><u><i>&nbsp;&nbsp;&nbsp;Programa Fines 2 Trayecto Secundario&nbsp;&nbsp;&nbsp;</i></u></strong> con orientación en <strong><u><i>&nbsp;&nbsp;&nbsp;{$orientacion}&nbsp;&nbsp;&nbsp;</i></u></strong>
    resolución <strong><u><i>&nbsp;&nbsp;&nbsp;{$resolucion}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
    <p >Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$fecha}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$presentacion}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
    ";

    if($observaciones) {
        $content .= "<p >Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$observaciones}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
    }
    // Write the content with justified text and line spacing
    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J'); 

    // Output PDF
    $pdf->Output("Constancia_Alumno_Regular.pdf", "I"); // Display in browser

    // Clean up temp QR file
    unlink($qrFile);
}

// Call the function
try {
    constancia_alumno_regular();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
