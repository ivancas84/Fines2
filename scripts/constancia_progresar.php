<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
require_once '../vendor/autoload.php'; // Ensure TCPDF is autoloaded

require_once '../fines-config.php';
require_once '../pedidos-config.php';


/**
 * script para generar constancia de pase en PDF
 * 
 * La impresion de asignaturas aprobadas y desaprobadas es diferente para el ticket y para el PDF
 */

use Fines2\AlumnoDAO;
use Fines2\AttachmentsDAO;
use Fines2\TicketsDAO;
use Pedidos\Tickets_;
use SqlOrganize\Utils\ValueTypesUtils;


$v = ValueTypesUtils::class;
$dbFines = \App\Context::getFinesDb();
$dbPedidos = \App\Context::getPedidosDb();



$alumno_id = $_POST['alumno_id'] ?? throw new Exception('El parámetro alumno_id no fue definido');

/** @var Fines2\Alumno_ */ $alumno = $dbFines->CreateDataProvider()->fetchEntityByParams("alumno", ["id" => $alumno_id]);
if(!$alumno){
    echo "<p>No se encontró un alumno asociado a ese id.</p>";
    die();
}

if(!$alumno->plan){
    echo "<p>Alumno sin plan, es necesario completar el plan del alumno para generar la constancia de pase.</p>";
    die();
}

$ultimaComision = AlumnoDAO::ultimaComisionAlumno($alumno->id);
$aniosCursados = implode(", ", $alumno->AniosCursados);
$nombres = ValueTypesUtils::toTitleCase($alumno->persona_->nombres);
$apellidos = mb_strtoupper($alumno->persona_->apellidos);
$numeroDocumento = $alumno->persona_->numero_documento;
$orientacion = $alumno->plan_->orientacion;
$resolucion = $alumno->plan_->resolucion;

$presentado = !empty($_POST['presentado']) ? $_POST['presentado'] : "quien corresponda";
$observaciones = !empty($_POST['observaciones']) ? $_POST['observaciones'] : "";
$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
$anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");
$actual_unix_timestamp = date("Ymdhi");
$upload_dir = "/wpsc/". date('Y') . "/" . date('m') . "/";
$filename = "{$actual_unix_timestamp}_pase_{$numeroDocumento}.pdf";
$save_path = $upload_dir . $filename;
$ticketId = AttachmentsDAO::CheckTicketIdByFilepath($save_path);

$s_ = ValueTypesUtils::htmlStrong(...);

$bodyStart = "
<p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
{$s_($apellidos)}, {$s_($nombres)} DNI Nº {$s_($numeroDocumento)} 
ha cursado los años {$s_($aniosCursados)} del Programa Fines 2 Trayecto Secundario con orientacion en {$s_($orientacion)}
resolución {$s_($resolucion)}, bajo el siguiente detalle:</p>
";


$body = $bodyStart;
if($alumno->CalificacionAprobada_Count > 0){
    $body .= "<h3>Calificaciones Aprobadas</h3>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr><th>Asignatura</th><th>Tramo</th><th>Nota</th></tr>";
        foreach($alumno->CalificacionAprobada_ as $calificacion){
            $body .= "<tr><td>{$calificacion->disposicion_->asignatura_->nombre}</td><td>{$calificacion->disposicion_->planificacion_->getTramo()}</td><td>{$calificacion->getNotaAprobada()}</td></tr>";
        }
    $body .= "</table>";
}

if($alumno->CalificacionDesaprobada_Count > 0){
    $body .= "<h3>Calificaciones Pendientes</h3>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr><th>Asignatura</th><th>Tramo</th></tr>";
        foreach($alumno->CalificacionDesaprobada_ as $calificacion){
            $body .= "<tr><td>{$calificacion->disposicion_->asignatura_->nombre}</td><td>{$calificacion->disposicion_->planificacion_->getTramo()}</td></tr>";
        }
    $body .= "</table>";
}


$bodyEnd = "<p>Se extiende la presente a pedido del interesado en La Plata el día {$s_($fecha)} para ser presentado ante {$s_($presentado)}.</p>";
$bodyEnd .= !empty($observaciones) ? $s_($observaciones) : ""; 

$body .= $bodyEnd;

$modify = $dbPedidos->CreateModifyQueries();

/** @var Tickets_ */ $ticket = TicketsDAO::CreateAndInsertTicketConstancia(
    $modify,
    "Constancia de Pase : " . $apellidos . ", " . $nombres,
    $numeroDocumento, 
    $body, $filename, $save_path
);

$modify->process();

$qrFile = $ticket->generateQR();

$pdf = new PDF\PDFConstanciaPase($alumno, $bodyStart, $bodyEnd, $qrFile);

if (!file_exists(PATH_UPLOAD_PEDIDOS2.$upload_dir)) {
    mkdir(PATH_UPLOAD_PEDIDOS2.$upload_dir, 0777, true);
}

// Save the PDF
$pdf->Output(PATH_UPLOAD_PEDIDOS2.$save_path, "F"); // Save to file

// Output PDF
$pdf->Output($filename, "I"); // Display in browser

// Clean up temp QR file
unlink($qrFile);

