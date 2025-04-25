<?php

require_once './includes/db_config.php';
require_once './vendor/autoload.php'; // Ensure TCPDF is autoloaded

require_once './class/PDFConstancias.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
    
    class PDFConstanciaPase extends PDFConstancias {
        

        public function __construct($data) {
            parent::__construct('P', 'mm', 'A4');
            $this->SetCreator(PDF_CREATOR);
            $this->SetAuthor('Escuela CENS Nº 462');
            $this->SetTitle("CONSTANCIA DE PASE");
            $this->SetMargins(20, 45, 20);
            $this->setPrintHeader(true);
            $this->setPrintFooter(true);
            $this->SetAutoPageBreak(true, 50);
            $this->AddPage();
            // Title
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 10, "CONSTANCIA DE PASE", 0, 1, 'C');
            $this->Ln(5);

            // Content
            $this->SetFont('helvetica', '', 10);
            $content = "<p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
<strong><u><i>{$data['apellidos']}, {$data['nombres']}</i></u></strong> DNI Nº <strong><u><i>{$data['numero_documento']}</i></u></strong> 
ha cursado los años <strong><u><i>{$data['anios_cursados']}</i></u></strong>
del <strong><u><i>Programa Fines 2 Trayecto Secundario</i></u></strong> con orientación en <strong><u><i>{$data['orientacion']}</i></u></strong>,
resolución <strong><u><i>{$data['resolucion']}</i></u></strong>, bajo el siguiente detalle.</p>";

            $this->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');
            $this->Ln(5);

            // Approved Grades Table
            if (!empty($data["calificaciones_aprobadas"])) {
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(0, 10, 'Calificaciones Aprobadas', 0, 1);
                $this->SetFont('helvetica', '', 10);

                $this->SetFillColor(220, 220, 220);
                $this->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
                $this->Cell(25, 8, 'Tramo', 1, 0, 'C', true);
                $this->Cell(25, 8, 'Nota', 1, 1, 'C', true);

                foreach ($data["calificaciones_aprobadas"] as $row) {
                    $nota = (round($row->nota_final) != 0) ? round($row->nota_final) : round($row->crec)."c";
                    $this->Cell(100, 8, $row->nombre, 1);
                    $this->Cell(25, 8, $row->tramo, 1);
                    $this->Cell(25, 8, $nota, 1, 1);
                }
                $this->Ln(5);
            }

            // Failed Grades Table
            if (!empty($data["calificaciones_desaprobadas"])) {
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(0, 10, 'Calificaciones Pendientes', 0, 1);
                $this->SetFont('helvetica', '', 10);

                $this->SetFillColor(220, 220, 220);
                $this->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
                $this->Cell(25, 8, 'Tramo', 1, 1, 'C', true);

                foreach ($data["calificaciones_desaprobadas"] as $row) {
                    $this->Cell(100, 8, $row->nombre, 1);
                    $this->Cell(25, 8, $row->tramo, 1, 1);
                }
            }

            $this->Ln(5);

            $content = "<p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>{$data['fecha']}</i></u></strong> para ser presentado ante <strong><u><i>{$data['presentacion']}</i></u></strong>.</p>";

            if (!empty($data['observaciones'])) {
                $content .= "<p>Observaciones:<strong><u><i>{$data['observaciones']}</i></u></strong></p>";
            }

            $this->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');
        }

        // Header method: Adds the logo and QR code on every page
        public function Header() {
            // Logo
            $this->Image('./images/logo.jpg', 20, 15, 80, 0, 'JPG');

            // QR Code
            if (!empty($this->qrFile)) {
                $this->Image($this->qrFile, 160, 15, 30, 30, 'PNG');
            }

            // Border Box (ensures it appears on all pages)
            $this->Rect(10, 10, 190, 277);
        }

        // Footer method: Adds signatures on every page
        public function Footer() {
            $this->SetY(-50); // Position from the bottom

            // Signatures
            $this->Image('./images/sello_cens.png', 85, 235, 30, 40, 'PNG');
            $this->Image('./images/firma_director_luis.png', 120, 240, 60, 35, 'PNG');
        }


      
     
    }    
    
    