<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded
require_once 'class/PdoFines.php';;
require_once 'class/PdoPedidos.php';;
require_once 'class/PDFConstancias.php';;


$pdoFines = new PdoFines();
$pdoPedidos = new PdoPedidos();
$pdfConstancias = new PDFConstancias();


$data = [
    "apellidos" => strtoupper($_POST["apellidos"]),
    "nombres" => ucwords(strtolower($_POST["nombres"])),
    "numero_documento" => $_POST["numero_documento"],
    "fecha" => $_POST["fecha"],
    "presentacion" => $_POST["presentacion"],
    "observaciones" => $_POST["observaciones"],
];


$data["body"] = $pdfConstancias->constanciaVacante($data);


$data = $pdoPedidos->insertarPedidoYDefinirDatosArchivo($data);
$pdfConstancias->OutputData($data);

?>