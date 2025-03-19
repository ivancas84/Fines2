<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'includes/queries_pedidos.php';
require_once 'includes/queries_fines.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $pdo->exec("SET NAMES 'utf8mb3'");

    $pdo_pedidos = new PDO("mysql:host=$db_host_pedidos;dbname=$db_name_pedidos;charset=utf8mb4", $db_user_pedidos, $db_pass_pedidos, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Call the function
try {
    //$url = pdoInsertarPedido($data);

    $id_calendario = $_GET['calendario'];
    $url = "NO VALIDO";
    $tomas = pdoFines_tomasAprobadas__ByCalendario($pdo, $id_calendario);
    foreach($tomas as $toma){
        $actual_unix_timestamp = time();
        $toma["root_dir"] = $rutaUploadPedidos;
        $toma["upload_dir"] = "/wpsc/". date('Y') . "/" . date('m') . "/";
        $toma["filename"] = "{$actual_unix_timestamp}_toma_{$toma['numero_documento']}.pdf";
        $toma["save_path"] = $toma["upload_dir"]."/".$toma["filename"];

        generar_toma_posesion($url, $toma);
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

/*$data["body"] = "La Dirección del CENS 462 de La Plata, hace constar por la presente que ";
$data["body"] .= $data['apellidos'] . ", ";
$data["body"] .= $data['nombres'] . " DNI N° ";
$data["body"] .= $data['numero_documento'] . " es alumno regular de ";
$data["body"] .= $data['anio'] . " año Programa Fines 2 Trayecto Secundario con orientación en ";
$data["body"] .= $data['orientacion'] . " resolución ";
$data["body"] .= $data['resolucion'];
    
if (!empty($data['observaciones'])) {
    $data["body"] .= " - " . $data['observaciones']; 
}*/


function generar_toma_posesion($url, $data) {
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
    $pdf = new TCPDF('P', 'mm', 'A4'); 
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Escuela CENS Nº 462');
    $pdf->SetTitle('Toma de Posesión');
    
    // Adjusted Margins
    $pdf->SetMargins(15, 20, 15);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    $pdf->AddPage();
    
    // Adjusted Full-Page Border
    $pdf->Rect(10, 10, 190, 270);

    // Header with logo and QR code
    $pdf->Image('./images/logo.jpg', 15, 15, 100, 0, 'JPG'); // Reduced logo width
    $pdf->Image($qrFile, 160, 15, 30, 30, 'PNG'); // QR Code
    
    $pdf->Ln(25);
    $pdf->SetAlpha(0.9);

    // Add images at the bottom
    $pdf->Image('./images/sello_cens.png', 85, 210, 30, 40, 'PNG'); // Bottom Center
    $pdf->Image('./images/firma_director_luis.png', 120, 220, 60, 35, 'PNG'); // Bottom Right

    $pdf->SetAlpha(1); // Reset transparency
   
    // Title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, "TOMA DE POSESION", 0, 1, 'C');
    $pdf->Ln(5);

    // Justified Content
    $pdf->SetFont('helvetica', '', 12);
    $content = "<p>La Dirección del CENS Nº 462 de La Plata realiza la toma de posesión docente con el siguiente detalle:</p>";
    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');
    $pdf->Ln(15);

    $pdf->SetFont('helvetica', '', 10);

    // Format date
 // Convert special characters to UTF-8 and encode for HTML rendering
$asignatura = htmlentities(mb_convert_encoding($data['asignatura_nombre'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
$sede = htmlentities(mb_convert_encoding($data['sede_nombre'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
$domicilio = htmlentities(mb_convert_encoding($data['domicilio_detalle'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
$horario = htmlentities(mb_convert_encoding($data['descripcion_horario'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
$resolucion = htmlentities(mb_convert_encoding($data['resolucion'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');

// Format date
$fecha_nacimiento = DateTime::createFromFormat('Y-m-d', $data["fecha_nacimiento"])->format('d/m/Y');
if(!empty($data["fecha_toma"])) $fecha_toma = DateTime::createFromFormat('Y-m-d', $data["fecha_toma"])->format('d/m/Y');
else $fecha_toma = DateTime::createFromFormat('Y-m-d', $data["fecha_inicio"])->format('d/m/Y');
$fecha_fin = DateTime::createFromFormat('Y-m-d', $data["fecha_fin"])->format('d/m/Y');

// Merge emails into a single string
$emails = [];
if (!empty(trim($data["email_abc"]))) array_push($emails, htmlentities($data["email_abc"], ENT_QUOTES, 'UTF-8'));
if (!empty(trim($data["email"]))) array_push($emails, htmlentities($data["email"], ENT_QUOTES, 'UTF-8'));
$emails = implode(", ", $emails);

// Generate table
$table = '
<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Docente</b></th></tr>
    <tr>
        <td><b>Nombre</b></td>
        <td colspan="3">' . mb_strtoupper($data["apellidos"], "UTF-8") . ', ' . mb_convert_case($data["nombres"], MB_CASE_TITLE, "UTF-8") . '</td>
    </tr>
    <tr>
        <td><b>CUIL</b></td><td>' . htmlentities($data["cuil"], ENT_QUOTES, 'UTF-8') . '</td>
        <td><b>Fecha de Nacimiento</b></td><td>' . $fecha_nacimiento . '</td>
    </tr>
    <tr>
        <td><b>Email</b></td><td colspan="3">' . $emails . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . htmlentities($data["descripcion_domicilio"], ENT_QUOTES, 'UTF-8') . '</td>
    </tr>
</table>
';

$pdf->writeHTML($table, true, false, false, false, '');
$pdf->Ln(5);

$table = '<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Cargo</b></th></tr>
    <tr>
        <td><b>Sede</b></td><td>' . $sede . '</td>
        <td><b>Comisión</b></td><td>' . htmlentities($data["pfid"], ENT_QUOTES, 'UTF-8') . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . $domicilio . '</td>
    </tr>
    <tr>
        <td><b>Horario</b></td><td colspan="3">' . $horario . '</td>
    </tr>
    <tr>
        <td><b>Fecha Toma</b></td><td>' . $fecha_toma . '</td>
        <td><b>Fecha Fin</b></td><td>' . $fecha_fin . '</td>
    </tr>
    <tr>
        <td><b>Asignatura</b></td><td>' . $asignatura . '</td>
        <td><b>Hs Cát</b></td><td>' . htmlentities($data["horas_catedra"], ENT_QUOTES, 'UTF-8') . '</td>
    </tr>
    <tr>
        <td><b>Tramo</b></td><td>' . $data["tramo"] . '</td>
        <td><b>Resolución</b></td><td>' . $resolucion . '</td>
    </tr>
</table>';

// Add table to PDF
$pdf->writeHTML($table, true, false, false, false, '');


    // Ensure directories exist
    if (!file_exists(dirname($data["root_dir"].$data["upload_dir"]))) {
        mkdir(dirname($data["root_dir"].$data["upload_dir"]), 0777, true);
    }

    // Save or Display PDF
    //$pdf->Output($data["save_path"], "F"); // Save to file
    $pdf->Output($data["filename"], "I"); // Display in browser

    // Clean up temp QR file
    unlink($qrFile);
}




?>
