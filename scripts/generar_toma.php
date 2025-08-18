<?php
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
mb_internal_encoding('UTF-8');

require_once '../vendor/autoload.php'; // Ensure TCPDF is autoloaded

require_once '../db-config.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Fines2\Toma_;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;

try {
    
    $toma_id = $_GET["toma_id"];

    $db = DbMy::getInstance();

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    /** @var Toma_ */ $toma = $dataProvider->fetchEntityByParams("toma", ["id" => $toma_id]);


    $root_dir = TOMAS_PATH;
    $upload_dir = $toma->curso_->comision_->calendario ;
    $filename = $toma->curso_->comision_->pfid . "_" . $toma->curso_->disposicion_->asignatura_->codigo . "_" . $toma->docente_->numero_documento . ".pdf";
    $save_path = $root_dir . $upload_dir . "/" . $filename;
    $fecha_toma = $toma->fecha_toma?->format("d/m/Y") ?? $toma->curso_->comision_->calendario_->inicio?->format("d/m/Y");
    $fecha_fin = $toma->curso_->comision_->calendario_->fin?->format("d/m/Y");
    
    
    // Generate table
    $table_docente = '
<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Docente</b></th></tr>
    <tr>
        <td><b>Nombre</b></td>
        <td colspan="3">' . $toma->docente_->getNombre() . '</td>
    </tr>
    <tr>
        <td><b>CUIL</b></td><td>' . $toma->docente_->cuil . '</td>
        <td><b>Fecha de Nacimiento</b></td><td>' . $toma->docente_->fecha_nacimiento->format("d/m/Y") . '</td>
    </tr>
    <tr>
        <td><b>Email</b></td><td colspan="3">' . $toma->docente_->getEmails() . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . $toma->docente_->descripcion_domicilio . '</td>
    </tr>
    <tr>
        <td><b>Teléfono</b></td><td colspan="3">' . $toma->docente_->telefono . '</td>
    </tr>
</table>
';

    $table_cargo =  '<table border="1" cellpadding="5">
        <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Cargo</b></th></tr>
        <tr>
            <td><b>Sede</b></td><td>' . $toma->curso_->comision_->sede_->nombre . '</td>
            <td><b>Comisión</b></td><td>' . $toma->curso_->comision_->pfid . '</td>
        </tr>
        <tr>
            <td><b>Domicilio</b></td><td colspan="3">' . $toma->curso_->comision_->sede_->domicilio_->getLabel() . '</td>
        </tr>
        <tr>
            <td><b>Horario</b></td><td colspan="3">' . $toma->curso_->descripcion_horario . '</td>
        </tr>
        <tr>
            <td><b>Fecha Toma</b></td><td>' . $fecha_toma . '</td>
            <td><b>Fecha Fin</b></td><td>' . $fecha_fin . '</td>
        </tr>
        <tr>
            <td><b>Asignatura</b></td><td>' . $toma->curso_->disposicion_->asignatura_->getLabel() . '</td>
            <td><b>Hs Cát</b></td><td>' . $toma->curso_->horas_catedra . '</td>
        </tr>
        <tr>
            <td><b>Tramo</b></td><td>' . $toma->curso_->comision_->planificacion_->getTramo() . '</td>
            <td><b>Resolución</b></td><td>' . $toma->curso_->comision_->planificacion_->plan_->resolucion . '</td>
        </tr>
    </table>';


    
    
    
    
    
       
    
    
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
    $pdf->Image(IMAGES_PATH . '/logo.jpg', 15, 15, 100, 0, 'JPG'); // Reduced logo width
    
    $pdf->Ln(25);
    $pdf->SetAlpha(0.9);

    // Add images at the bottom
    $pdf->Image(IMAGES_PATH . '/sello_cens.png', 85, 210, 30, 40, 'PNG'); // Bottom Center
    $pdf->Image(IMAGES_PATH . '/firma_director_luis.png', 120, 220, 60, 35, 'PNG'); // Bottom Right

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

    $pdf->writeHTML($table_docente, true, false, false, false, '');
    $pdf->Ln(5);

    // Add table to PDF
    $pdf->writeHTML($table_cargo, true, false, false, false, '');

    // Ensure directories exist
    if (!file_exists($root_dir.$upload_dir)) {
        $res = mkdir($root_dir.$upload_dir, 0777, true);
        if($res === false) throw new Exception("directorio " . $root_dir.$upload_dir . " no creado");
    }

    // Save or Display PDF
    $pdf->Output($save_path, "F"); // Save to file
    //$pdf->Output($data["filename"], "I"); // Display in browser







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
            
            $toPrimary = "icastaneda@abc.gob.ar";
            $toSecondary = "";
            //$toPrimary = $toma->docente_->email_abc;
            //$toSecondary = $toma->docente_->email;
            $bcc = EMAIL_DOCENTES_BCC;
        
            $mail->setFrom(EMAIL_DOCENTES_FROM_ADDRESS);
            if(!empty($toPrimary)) $mail->addAddress($toPrimary);
            if(!empty($toSecondary)) $mail->addAddress($toSecondary);
            $mail->addBCC($bcc);
            $mail->Subject = mb_encode_mimeheader("Toma de Posesión " . $toma->docente_->getNombre() . " CUIL " . $toma->docente_->cuil . " en cargo " . $toma->curso_->comision_->pfid . " " . $toma->curso_->disposicion_->asignatura_->getLabel(), "UTF-8", "B");
            $mail->Body = "<p>Hola {$toma->docente_->getNombre()}, usted ha recibido este email porque fue designado/a en la asignatura <strong>{$toma->curso_->disposicion_->asignatura_->getLabel()}</strong> de sede {$toma->curso_->comision_->sede_->nombre}</p>
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
            $mail->isHTML(true);
            $mail->addAttachment($save_path);
            
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



} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}







?>
