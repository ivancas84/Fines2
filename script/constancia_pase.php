<?php

use App\Context;
use Fines2\Model\Alumno_;
use Fines2\Pdf\PDFConstanciaPase;
use Pedidos\DataAccess\AttachmentsDAO;
use Pedidos\DataAccess\TicketsDAO;
use Pedidos\Model\Attachments_;
use Pedidos\Model\Threads_;
use Pedidos\Model\Tickets_;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';+


/** @var Db */ $dbFines = Context::getFinesDb();
/** @var Db */ $dbPedidos = Context::getPedidosDb();


$alumno_id = $_POST['alumno_id'] ?? throw new Exception('El parámetro alumno_id no fue definido');

/** @var Alumno_ */ $alumno = $dbFines->CreateDataProvider()->fetchEntityByParams("alumno", ["id" => $alumno_id]);
if(!$alumno){
    echo "<p>No se encontró un alumno asociado a ese id.</p>";
    die();
}

if(!$alumno->plan){
    echo "<p>Alumno sin plan, es necesario completar el plan del alumno para generar la constancia de pase.</p>";
    die();
}

$alumno->initializeCalifacionesArrays();

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

if(!is_null($ticketId)){
    /** @var Tickets_ */$ticket = $dbPedidos->CreateDataProvider()->fetchEntityByParams("tickets", ["id" => $ticketId]);
    $url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket->id . "&auth-code=" . $ticket->auth_code;
    echo "<p>Ya existe una constancia generada para este DNI. Puede descargarla nuevamente desde el siguiente enlace:</p>";
    echo "<p><a href='$url' target='_blank'>$url</a></p>";
    die();
}

$s_ = ValueTypesUtils::htmlStrong(...);

$bodyStart = "
<p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
{$s_($apellidos)}, {$s_($nombres)} DNI Nº {$s_($numeroDocumento)} 
ha cursado los años {$s_($aniosCursados)} del Programa Fines 2 Trayecto Secundario con orientacion en {$s_($orientacion)}
resolución {$s_($resolucion)}, bajo el siguiente detalle:</p>
";


$body = $bodyStart;

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


/** @var ModifyQueries */ $modify = $dbPedidos->CreateModifyQueries();

/** @var Tickets_ */ $ticket = TicketsDAO::CreateAndInsertTicketConstancia(
    $modify,
    "Constancia de Pase : " . $apellidos . ", " . $nombres,
    $numeroDocumento, 
    $body, $filename, $save_path
);

$modify->process();

$qrFile = $ticket->generateQRFile();


$pdf = new PDFConstanciaPase($alumno, $bodyStart, $bodyEnd, $qrFile);
if (!file_exists(PATH_UPLOAD_PEDIDOS.$upload_dir)) {
    mkdir(PATH_UPLOAD_PEDIDOS.$upload_dir, 0777, true);
}

// Save the PDF
$pdf->Output(PATH_UPLOAD_PEDIDOS.$save_path, "F"); // Save to file

// Output PDF
$pdf->Output($filename, "I"); // Display in browser

// Clean up temp QR file
unlink($qrFile);

