<?php

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Pedidos\DataAccess\AttachmentsDAO;
use Pedidos\Model\Attachments_;
use Pedidos\Model\Threads_;
use Pedidos\Model\Tickets_;

header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';



$dbFines = \App\Context::getFinesDb();
$dbPedidos = \App\Context::getPedidosDb();


$actual_unix_timestamp = date("Ymdhi"); //solo permitira generar uno por mes
$upload_dir = "/wpsc/". date('Y') . "/" . date('m') . "/";
$filename = "{$actual_unix_timestamp}_regular_{$_POST["numero_documento"]}.pdf";
$save_path = $upload_dir . $filename;
$ticketId = AttachmentsDAO::CheckTicketIdByFilepath($save_path);

$body = "
<p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
<strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['apellidos']}, {$_POST['nombres']}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['numero_documento']}&nbsp;&nbsp;&nbsp;</i></u></strong> 
es alumno/a regular de <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['anio']}&nbsp;&nbsp;&nbsp;</i></u></strong> año <strong><u><i>&nbsp;&nbsp;&nbsp;Programa Fines 2 Trayecto Secundario&nbsp;&nbsp;&nbsp;</i></u></strong> con orientación en <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['orientacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>
resolución <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['resolucion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
<p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['fecha']}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['presentado']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
";

if (!empty($_POST['observaciones'])) {
    $body .= "<p>Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$_POST['observaciones']}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
}



$ticket = new Tickets_();
$ticket->subject = "Constancia de alumno regular : " . $_POST["apellidos"] . ", " . $_POST["nombres"];
$ticket->status = 4;
$ticket->category = 10;
$ticket->date_closed = new DateTime();
$ticket->cust_24 = $_POST["numero_documento"];
$ticket->cust_28 = "Válido por 30 días";

$thread = new Threads_();
$thread->ticket = $ticket->id;
$thread->body = $body;

$attachment = new Attachments_();
$attachment->name = $filename;
$attachment->file_path = $save_path;
$attachment->is_image = 1;
$attachment->source_id = $thread->id;
$attachment->ticket_id = $ticket->id;

$modify = $dbPedidos->CreateModifyQueries();
$modify->InsertSql($ticket);
$modify->InsertSql($thread);
$modify->InsertSql($attachment);

$thread->attachments = $attachment->id;
$modify->UpdateKeySqlById($thread, "attachments");
$modify->process();

$url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket->id . "&auth-code=" . $ticket->auth_code;

$options = new QROptions([
    'eccLevel' => EccLevel::L,
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
    'scale' => 5,
]);

$qrcode = (new QRCode($options))->render($url);

// Save the QR Code as a temporary file
$qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));



// Create PDF instance
$pdf = new \TCPDF('L', 'mm', 'A5'); // 'L' for Landscape, 'A5' for A5 paper size
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela CENS Nº 462');
$pdf->SetTitle('Constancia de Alumno Regular');
$pdf->SetMargins(20, 30, 20);
$pdf->setPrintHeader(false); // Avoid header line
$pdf->AddPage();
$pdf->Rect(10, 10, 190, 125); // Full-page border

// Header with logo and QR code
$pdf->Image(IMAGES_PATH .'logo.jpg', 20, 15, 120, 0, 'JPG'); // Logo occupies 2/3
$pdf->Image($qrFile, 160, 15, 30, 30, 'PNG'); // QR occupies 1/3
$pdf->Ln(15);
$pdf->SetAlpha(0.9);

// Add images at the bottom
$pdf->Image(IMAGES_PATH .'sello_cens.png', 85, 85, 30, 40, 'PNG'); // Bottom Center
$pdf->Image(IMAGES_PATH .'firma_director_luis.png', 120, 90, 60, 35, 'PNG'); // Bottom Right

$pdf->SetAlpha(1); // Reset transparency
// Title
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, "CONSTANCIA DE ALUMNO REGULAR", 0, 1, 'C');
$pdf->Ln(5);

// Justify the content with interlineado
$pdf->SetFont('helvetica', '', 10);


// Write the content with justified text and line spacing
$pdf->writeHTMLCell(0, 0, '', '', $body, 0, 1, false, true, 'J');


    // Ensure directories exist

if (!file_exists(PATH_UPLOAD_PEDIDOS.$upload_dir)) {
    mkdir(PATH_UPLOAD_PEDIDOS.$upload_dir, 0777, true);
}

// Save the PDF
$pdf->Output(PATH_UPLOAD_PEDIDOS.$save_path, "F"); // Save to file

// Output PDF
$pdf->Output($filename, "I"); // Display in browser

// Clean up temp QR file
unlink($qrFile);

