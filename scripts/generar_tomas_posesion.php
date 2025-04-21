<?php
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
mb_internal_encoding('UTF-8');


require_once 'includes/db_config.php';
require_once 'includes/queries_pedidos.php';
require_once 'includes/queries_fines.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

require_once 'includes/db_config.php';

require_once 'class/PdoFines.php';

$pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
try {
    $db_host = DB_HOST_FINES;
    $db_name = DB_NAME_FINES;
    $db_user = DB_USER_FINES;
    $db_pass = DB_PASS_FINES;
    $db_host_pedidos = DB_HOST_PEDIDOS;
    $db_name_pedidos = DB_NAME_PEDIDOS;
    $db_user_pedidos = DB_USER_PEDIDOS; 
    $db_pass_pedidos = DB_PASS_PEDIDOS; 

    $pdo_pedidos = new PDO("mysql:host=$db_host_pedidos;dbname=$db_name_pedidos;charset=utf8mb4", $db_user_pedidos, $db_pass_pedidos, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Call the function
try {
    

    $id_calendario = "202502110007";
    
    $tomas = pdoFines_tomasAprobadas__ByCalendario($pdo, $id_calendario);
    echo "CANTIDAD DE TOMAS " . count($tomas) . "<br>";

    foreach($tomas as $toma){
        $actual_unix_timestamp = time();
        $toma["root_dir"] = PATH_UPLOAD_PEDIDOS;
        $toma["upload_dir"] = "/wpsc/". date('Y') . "/" . date('m') . "/";
        $toma["filename"] = "{$actual_unix_timestamp}_toma_{$toma['numero_documento']}.pdf";
        $toma["save_path"] = $toma["root_dir"] . $toma["upload_dir"] . $toma["filename"];

        $docente = mb_strtoupper($toma["apellidos"], "UTF-8") . ', ' . mb_convert_case($toma["nombres"], MB_CASE_TITLE, "UTF-8");
        $asignatura = htmlentities(mb_convert_encoding($toma['asignatura_nombre'] . " " . $toma['asignatura_codigo'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
        $sede = htmlentities(mb_convert_encoding($toma['sede_nombre'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
        $domicilio = htmlentities(mb_convert_encoding($toma['domicilio_detalle'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
        $horario = htmlentities(mb_convert_encoding($toma['descripcion_horario'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
        $resolucion = htmlentities(mb_convert_encoding($toma['resolucion'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
        $pfid = htmlentities($toma["pfid"], ENT_QUOTES, 'UTF-8');
        $telefono = htmlentities(mb_convert_encoding($toma['telefono'], 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');

        // Format date
        $fecha_nacimiento = DateTime::createFromFormat('Y-m-d', $toma["fecha_nacimiento"])->format('d/m/Y');
        if(!empty($toma["fecha_toma"])) $fecha_toma = DateTime::createFromFormat('Y-m-d', $toma["fecha_toma"])->format('d/m/Y');
        else $fecha_toma = DateTime::createFromFormat('Y-m-d', $toma["fecha_inicio"])->format('d/m/Y');
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $toma["fecha_fin"])->format('d/m/Y');
        
        // Merge emails into a single string
        $emails = [];
        if (!empty(trim($toma["email_abc"]))) array_push($emails, htmlentities($toma["email_abc"], ENT_QUOTES, 'UTF-8'));
        if (!empty(trim($toma["email"]))) array_push($emails, htmlentities($toma["email"], ENT_QUOTES, 'UTF-8'));
        $emails = implode(", ", $emails);
        $cuil = htmlentities($toma["cuil"], ENT_QUOTES, 'UTF-8');

        $toma["subject"] = "Toma de Posesión del docente " . $docente . " CUIL " . $cuil . " en cargo " . $pfid . " " . $asignatura;
        
        // Generate table
        $toma["data_docente"] = '
<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Docente</b></th></tr>
    <tr>
        <td><b>Nombre</b></td>
        <td colspan="3">' . $docente . '</td>
    </tr>
    <tr>
        <td><b>CUIL</b></td><td>' . $cuil . '</td>
        <td><b>Fecha de Nacimiento</b></td><td>' . $fecha_nacimiento . '</td>
    </tr>
    <tr>
        <td><b>Email</b></td><td colspan="3">' . $emails . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . htmlentities($toma["descripcion_domicilio"], ENT_QUOTES, 'UTF-8') . '</td>
    </tr>
    <tr>
        <td><b>Teléfono</b></td><td colspan="3">' . $telefono . '</td>
    </tr>
</table>
';

        $toma["body"] =  '<table border="1" cellpadding="5">
        <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Cargo</b></th></tr>
        <tr>
            <td><b>Sede</b></td><td>' . $sede . '</td>
            <td><b>Comisión</b></td><td>' . $pfid . '</td>
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
            <td><b>Hs Cát</b></td><td>' . htmlentities($toma["horas_catedra"], ENT_QUOTES, 'UTF-8') . '</td>
        </tr>
        <tr>
            <td><b>Tramo</b></td><td>' . $toma["tramo"] . '</td>
            <td><b>Resolución</b></td><td>' . $resolucion . '</td>
        </tr>
    </table>';


        if(!empty($toma["archivo"])){
            echo $pfid . " " . $docente . " " . $telefono . " (ya existe) " . $toma["archivo"] . "<br>"; 
            continue;
        }

        $url = pdoInsertarPedido($toma);
        generar_toma_posesion($url, $toma);
        pdoFines_updateArchivoTomaById($pdo, $url, $toma["id"]);
        echo $pfid . " " . $docente . " " . $telefono . " (generado) " . $url; 
        sendEmail($toma["save_path"], $sede, $pfid, $toma['asignatura_nombre'] . " " . $toma['asignatura_codigo'], $docente, $toma["email_abc"], $toma["email"]);
        
        sleep(5); // Wait for 30 seconds before processing the next item to avoid multiple emails

    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


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

   


    $pdf->writeHTML($data["data_docente"], true, false, false, false, '');
    $pdf->Ln(5);



    // Add table to PDF
    $pdf->writeHTML($data["body"], true, false, false, false, '');


    
    // Ensure directories exist
    if (!file_exists(dirname($data["root_dir"].$data["upload_dir"]))) {
        mkdir(dirname($data["root_dir"].$data["upload_dir"]), 0777, true);
    }


    // Save or Display PDF
    $pdf->Output($data["save_path"], "F"); // Save to file
    //$pdf->Output($data["filename"], "I"); // Display in browser

    // Clean up temp QR file
    unlink($qrFile);
}



function sendEmail($path_toma, $sede_nombre, $comision_pfid, $asignatura_nombre, $docente_nombre, $email_abc, $email) {
    $maxAttempts = 3;
    $attempt = 0;
    $sent = false;

    while ($attempt < $maxAttempts && !$sent) {
        $mail = new PHPMailer(true);


    try {
        $mail->isSMTP();
        $mail->Host = EMAIL_DOCENTES_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_DOCENTES_USER;
        $mail->Password = EMAIL_DOCENTES_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $attachmentPath = $path_toma;
        //$toPrimary = "icastaneda@abc.gob.ar";
        //$toSecondary = "";
        $toPrimary = $email_abc;
        $toSecondary = $email;
        $bcc = EMAIL_DOCENTES_BCC;
        $subject = "Toma de posesión: {$comision_pfid} {$asignatura_nombre}";
        $subject =  mb_encode_mimeheader($subject, "UTF-8", "B");
        $body = "<p>Hola {$docente_nombre}, usted ha recibido este email porque fue designado/a en la asignatura <strong>{$asignatura_nombre}</strong> de sede {$sede_nombre}</p>
<p><strong>Para confirmar su toma de posesión, necesitamos que responda este email indicando que la información del documento adjunto es correcta.</strong></p>
<p>Se recuerda que al aceptar su toma de posesión, usted se compromete a:</p>
  <ul>
    <li>Completar las planillas de finalización en tiempo y forma.</li>
    <li>Participar de las mesas de examen cuando se lo requiera.</li>
    <li>Atender a la brevedad cualquier solicitud indicada por el CENS.</li>
  </ul>
</p>
<p><strong>Para cualquier duda comuníquese vía mensaje o audio de WhatsApp al número 2216713326</strong></p>
<br>
Saluda a Usted muy atentamente:
<br>
Equipo de Coordinadores del Plan Fines 2 CENS 462
<br><a href=\"https://planfines2.com.ar\">https://planfines2.com.ar</a>";
        
        $mail->setFrom(EMAIL_DOCENTES_FROM_ADDRESS);
        if(!empty($toPrimary)) $mail->addAddress($toPrimary);
        if(!empty($toSecondary)) $mail->addAddress($toSecondary);
        $mail->addBCC($bcc);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true);
        $mail->addAttachment($attachmentPath);
        
        $mail->send();
        $sent = true;
        echo "Message has been sent<br>";
    } catch (Exception $e) {
        $attempt++;
        if ($attempt < $maxAttempts) {
            sleep(5); // Wait 5 seconds before retry
        }
    }

    }
    if (!$sent) {
        echo "Message could not be sent after {$maxAttempts} attempts.<br>";
    }
}




?>
