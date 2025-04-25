<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded
require_once 'class/PdoPedidos.php';;
require_once 'class/PDFConstanciaPase.php';;

$pdoPedidos = new PdoPedidos();



$data = [
    "calificaciones_aprobadas" => json_decode($_POST["calificaciones_aprobadas"]),
    "calificaciones_desaprobadas" => json_decode($_POST["calificaciones_desaprobadas"]),
    "anios_cursados" => $_POST["anios_cursados"],
    "apellidos" => strtoupper($_POST["apellidos"]),
    "nombres" => ucwords(strtolower($_POST["nombres"])),
    "numero_documento" => $_POST["numero_documento"],
    "resolucion" => $_POST["resolucion"],
    "fecha" => $_POST["fecha"],
    "presentacion" => $_POST["presentacion"],
    "orientacion" => $_POST["orientacion"],
    "observaciones" => $_POST["observaciones"],
    "titulo" => "CONSTANCIA DE PASE"
];


$data["body"] = "La Dirección del CENS 462 de La Plata, hace constar por la presente que ";
$data["body"] .= $data['apellidos'] .    ", ";
$data["body"] .= $data['nombres'] . " DNI N° ";
$data["body"] .= $data['numero_documento'] . " ha cursado los años ";
$data["body"] .= $data['anios_cursados'] . " del Programa Fines 2 Trayecto Secundario con orientación en ";
$data["body"] .= $data['orientacion'] . " resolución ";
$data["body"] .= $data['resolucion'] . ", aprobando " . count($data['calificaciones_aprobadas']) . " y adeudando " . count($data['calificaciones_desaprobadas']) . " materias.";

if (!empty($data['observaciones'])) {
    $data["body"] .= " - " . $data['observaciones']; 
}



$data = $pdoPedidos->insertarPedidoYDefinirDatosArchivo($data);
$pdfConstanciaPase = new PDFConstanciaPase($data);
$pdfConstanciaPase->OutputBase($data["upload_dir"], $data["filename"]);





?>