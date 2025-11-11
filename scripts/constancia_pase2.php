<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once '../fines-config.php';
require_once '../pedidos-config.php';

require_once '../vendor/autoload.php'; // Ensure TCPDF is autoloaded

/**
 * script para generar constancia de pase en PDF
 * 
 * La impresion de asignaturas aprobadas y desaprobadas es diferente para el ticket y para el PDF
 */
use Fines2\AttachmentsDAO;
use Fines2\TicketsDAO;
use Fines2\QRUtils;
use Pedidos\Attachments_;
use Pedidos\Threads_;
use Pedidos\Tickets_;
use SqlOrganize\Utils\ValueTypesUtils;


$v = ValueTypesUtils::class;
$dbFines = \App\Context::getFinesDb();
$dbPedidos = \App\Context::getPedidosDb();



$persona_id = $_POST['persona_id'] ?? throw new Exception('El parámetro persona_id no fue definido');

/** @var Fines2\Alumno_ */ $alumno = $dbFines->CreateDataProvider()->fetchEntityByParams("alumno", ["persona" => $persona_id]);
if(!$alumno){
    echo "<p>No se encontró un alumno asociado a ese id.</p>";
    die();
}

if(!$alumno->plan){
    echo "<p>Alumno sin plan, es necesario completar el plan del alumno para generar la constancia de pase.</p>";
    die();
}

$alumno->initializeCalifacionesArrays();

$aniosCursados = esc_attr(implode(", ", $alumno->AniosCursados));
$nombres = esc_attr(ValueTypesUtils::toTitleCase($alumno->persona_->nombres));
$apellidos = esc_attr(mb_strtoupper($alumno->persona_->apellidos));
$numero_documento = esc_attr($alumno->persona_->numero_documento);
$orientacion = esc_attr($alumno->plan_->orientacion);
$resolucion = esc_attr($alumno->plan_->resolucion);

$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
$anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");
$actual_unix_timestamp = date("Ymdhi");
$upload_dir = "/wpsc/". date('Y') . "/" . date('m') . "/";
$filename = "{$actual_unix_timestamp}_pase_{$numero_documento}.pdf";
$save_path = $upload_dir . $filename;
$ticketId = AttachmentsDAO::CheckTicketIdByFilepath($save_path);

$s_ = ValueTypesUtils::htmlStrong(...);

$bodyStart = "
<p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
{$s_($apellidos)}, {$s_($nombres)} DNI Nº {$_($numero_documento)} 
ha cursado los años {$_($anios_cursados)} del Programa Fines 2 Trayecto Secundario con orientacion en {$s_($orientacion)}
resolución {$s_($resolucion)}, bajo el siguiente detalle:</p>
";


$body = $bodyStart;
if($alumno->CalificacionAprobada_Count > 0){
    $body .= "<h3>Calificaciones Aprobadas</h3>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr><th>Asignatura</th><th>Tramo</th><th>Nota</th></tr>";
        foreach($alumno->CalificacionesAprobadas as $calificacion){
            $body .= "<tr><td>{$calificacion->disposicion_->asignatura_->nombre}</td><td>{$calificacion->disposicion_->planificacion_->getTramo()}</td><td>{$calificacion->getNotaAprobada()}</td></tr>";
        }
    $body .= "</table>";
}

if($alumno->CalificacionDesaprobada_Count > 0){
    $body .= "<h3>Calificaciones Pendientes</h3>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr><th>Asignatura</th><th>Tramo</th></tr>";
        foreach($alumno->CalificacionesDesaprobadas as $calificacion){
            $body .= "<tr><td>{$calificacion->disposicion_->asignatura_->nombre}</td><td>{$calificacion->disposicion_->planificacion_->getTramo()}</td></tr>";
        }
    $body .= "</table>";
}


$bodyEnd = !empty($_POST['observaciones']) ? $_POST['observaciones'] : ""; 

$body .= $bodyEnd;

$modify = $dbPedidos->CreateModifyQueries();

/** @var Tickets_ */ $ticket = TicketsDAO::CreateAndInsertTicketConstancia(
    $modify,
    "Constancia de Pase : " . $apellidos . ", " . $nombres,
    $numero_documento, 
    $body, $filename, $save_path
);

$modify->process();

$qrFile = $ticket->generateQR();


// Create PDF instance
$pdf = new TCPDF('P', 'mm', 'A4'); // 'L' for Landscape, 'A5' for A5 paper size
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela CENS Nº 462');
$pdf->SetTitle('CONSTANCIA DE PASE');
$pdf->SetMargins(20, 45, 20);
$pdf->setPrintHeader(true); // Avoid header line
$pdf->setPrintFooter(true);
$pdf->SetAutoPageBreak(true, 50);
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, "CONSTANCIA DE PASE", 0, 1, 'C');
$pdf->Ln(5);

// Content
$pdf->SetFont('helvetica', '', 10);






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

if (!file_exists(PATH_UPLOAD_PEDIDOS2.$upload_dir)) {
    mkdir(PATH_UPLOAD_PEDIDOS2.$upload_dir, 0777, true);
}

// Save the PDF
$pdf->Output(PATH_UPLOAD_PEDIDOS2.$save_path, "F"); // Save to file

// Output PDF
$pdf->Output($filename, "I"); // Display in browser

// Clean up temp QR file
unlink($qrFile);

