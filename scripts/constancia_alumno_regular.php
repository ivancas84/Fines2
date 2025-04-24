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
    "apellidos" => strtoupper(filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING)),
    "nombres" => ucwords(strtolower(filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_STRING))),
    "numero_documento" => filter_input(INPUT_POST, 'numero_documento', FILTER_SANITIZE_STRING),
    "anio" => filter_input(INPUT_POST, 'anio_en_curso', FILTER_SANITIZE_STRING),
    "resolucion" => filter_input(INPUT_POST, 'resolucion', FILTER_SANITIZE_STRING),
    "fecha" => filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING),
    "presentacion" => filter_input(INPUT_POST, 'presentado', FILTER_SANITIZE_STRING),
    "orientacion" => filter_input(INPUT_POST, 'orientacion', FILTER_SANITIZE_STRING),
    "observaciones" => filter_input(INPUT_POST, 'observaciones', FILTER_SANITIZE_STRING),
    "titulo" => "CONSTANCIA DE ALUMNO REGULAR",
];

$data["subject"] = "Constancia de alumno regular : " . $data["apellidos"] . ", " . $data["nombres"];
$data["body"] = $pdfConstancias->constanciaAlumnoRegular($data);
$data = $pdoPedidos->insertarPedidoYDefinirDatosArchivo($data);


$pdfConstancias->OutputData($data);

?>
