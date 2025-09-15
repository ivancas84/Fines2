<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once '../fines-config.php';
require_once '../vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Pedidos\Tickets_;
use SqlOrganize\Utils\ValueTypesUtils;

$dbFines = \App\Context::getFinesDb();
$dbPedidos = \App\Context::getPedidosDb();


$actual_unix_timestamp = date("Ymd");
$_POST["upload_dir"] = "/wpsc/". date('Y') . "/" . date('m') . "/";
$_POST["filename"] = "{$actual_unix_timestamp}_constancia_{$numero_documento}.pdf";
$_POST["save_path"] = $_POST["upload_dir"] . $_POST["filename"];

$_POST["body"] = "La Dirección del CENS 462 de La Plata, hace constar por la presente que ";
$_POST["body"] .= $_POST['apellidos'] . ", ";
$_POST["body"] .= $_POST['nombres'] . " DNI N° ";
$_POST["body"] .= $_POST['numero_documento'] . " es alumno regular de ";
$_POST["body"] .= $_POST['anio'] . " año Programa Fines 2 Trayecto Secundario con orientación en ";
$_POST["body"] .= $_POST['orientacion'] . " resolución ";
$_POST["body"] .= $_POST['resolucion'];
    
if (!empty($_POST['observaciones'])) {
    $_POST["body"] .= " - " . $_POST['observaciones']; 
}


function generar_constancia_titulo_tramite($url, $data) {
    // Create QR Code

    $options = new QROptions([
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'scale' => 5,
    ]);

    $qrcode = (new QRCode($options))->render($url);

    // Save the QR Code as a temporary file
    $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

    // Create PDF instance
    $pdf = new TCPDF('L', 'mm', 'A5'); // 'L' for Landscape, 'A5' for A5 paper size
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Escuela CENS Nº 462');
    $pdf->SetTitle('Constancia de Certificado de Estudio en Trámite');
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
    $pdf->Cell(0, 10, "CONSTANCIA DE CERTIFICADO DE ESTUDIO EN TRÁMITE", 0, 1, 'C');
    $pdf->Ln(5);

    // Justify the content with interlineado
    $pdf->SetFont('helvetica', '', 10);
    $content = "
    <p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
    <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['apellidos']}, {$data['nombres']}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['numero_documento']}&nbsp;&nbsp;&nbsp;</i></u></strong> 
    tiene en trámite un CERTIFICADO ANALÍTICO DE ESTUDIOS <strong><u><i>&nbsp;&nbsp;&nbsp;COMPLETO&nbsp;&nbsp;&nbsp;</i></u></strong> de <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['anio']}&nbsp;&nbsp;&nbsp;</i></u></strong> año <strong><u><i>&nbsp;&nbsp;&nbsp;Programa Fines 2 Trayecto Secundario&nbsp;&nbsp;&nbsp;</i></u></strong> con orientación en <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['orientacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>
    resolución <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['resolucion']}&nbsp;&nbsp;&nbsp;</i></u></strong> adeudando <strong><u><i>&nbsp;&nbsp;&nbsp;Ninguna Materia&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
    <p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['fecha']}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['presentacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
    ";

    if (!empty($data['observaciones'])) {
        $content .= "<p>Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$data['observaciones']}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
    }

    // Write the content with justified text and line spacing
    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');

    

    // Ensure directories exist
    if (!file_exists(dirname(PATH_UPLOAD_PEDIDOS.$data["upload_dir"]))) {
        mkdir(dirname(PATH_UPLOAD_PEDIDOS.$data["upload_dir"]), 0777, true);
    }

    // Save the PDF
    $pdf->Output(PATH_UPLOAD_PEDIDOS.$data["save_path"], "F"); // Save to file
    // Output PDF
    $pdf->Output($data["filename"], "I"); // Display in browser

    // Clean up temp QR file
    unlink($qrFile);
}


// Call the function
try {
    $data["subject"] = "Constancia de certificado completo : " . $data["apellidos"] . ", " . $data["nombres"];
    
    $ticket = new Tickets_();
    
    
    $url = pdoInsertarPedido($data);
    generar_constancia_titulo_tramite($url, $data);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



?>
