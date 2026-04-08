<?php

namespace Fines2\Pdf;

use Fines2\Model\Alumno_;

class PDFConstanciaPase extends \TCPDF {
    
    protected $qrFile;

    public function __construct(Alumno_ $alumno, string $bodyStart, string $bodyEnd, $qrFile) {
        
        $this->qrFile = $qrFile;
        parent::__construct('P', 'mm', 'A4');
        $this->SetAlpha(0.9);
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Escuela CENS Nº 462');
        $this->SetTitle("CONSTANCIA DE PASE");  
        $this->SetMargins(20, 45, 20);
        $this->setPrintHeader(true);
        $this->setPrintFooter(true);
        $this->SetAutoPageBreak(true, 50);
        $this->AddPage();

        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, "CONSTANCIA DE PASE", 0, 1, 'C');
        $this->Ln(5);

        // Content
        $this->SetFont('helvetica', '', 10);
        $this->writeHTMLCell(0, 0, '', '', $bodyStart, 0, 1, false, true, 'J');
        $this->Ln(5);

        // Approved Grades Table
        if($alumno->CalificacionAprobada_Count > 0){
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(0, 10, 'Calificaciones Aprobadas', 0, 1);
            $this->SetFont('helvetica', '', 10);

            $this->SetFillColor(220, 220, 220);
            $this->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
            $this->Cell(25, 8, 'Tramo', 1, 0, 'C', true);
            $this->Cell(25, 8, 'Nota', 1, 1, 'C', true);

            foreach($alumno->CalificacionAprobada_ as $calificacion){
                $this->Cell(100, 8, $calificacion->disposicion_->asignatura_->nombre, 1);
                $this->Cell(25, 8, $calificacion->disposicion_->planificacion_->getTramo(), 1);
                $this->Cell(25, 8, $calificacion->getNotaAprobada(), 1, 1);
            }
            $this->Ln(5);
        }

        // Approved Grades Table
        if($alumno->CalificacionDesaprobada_Count > 0){
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(0, 10, 'Calificaciones Pendientes', 0, 1);
            $this->SetFont('helvetica', '', 10);

            $this->SetFillColor(220, 220, 220);
            $this->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
            $this->Cell(25, 8, 'Tramo', 1, 0, 'C', true);
            $this->Cell(25, 8, 'Nota', 1, 1, 'C', true);

            foreach($alumno->CalificacionDesaprobada_ as $calificacion){
                $this->Cell(100, 8, $calificacion->disposicion_->asignatura_->nombre, 1);
                $this->Cell(25, 8, $calificacion->disposicion_->planificacion_->getTramo(), 1);
                $this->Cell(25, 8, $calificacion->getNotaAprobada(), 1, 1);
            }
            $this->Ln(5);
        }

        $this->writeHTMLCell(0, 0, '', '', $bodyEnd, 0, 1, false, true, 'J');
    }


        // Header method: Adds the logo and QR code on every page
        public function Header() {
            $this->Image(IMAGES_PATH .'logo.jpg', 20, 15, 120, 0, 'JPG'); // Logo occupies 2/3
            $this->Image($this->qrFile, 160, 15, 30, 30, 'PNG'); // QR occupies 1/3
            
            // Add "VALIDACION" text below QR code
            $this->SetFont('helvetica', 'B', 8);
            $this->SetXY(160, 45); // Position below QR (15 + 30 + 1 = 46)
            $this->Cell(30, 4, 'VERIFICACION', 0, 0, 'C');
            
            $this->Ln(15);
            $this->Rect(10, 10, 190, 277);
        }

        // Footer method: Adds signatures on every page
        public function Footer() {
            $this->SetAlpha(0.9);
            
            // Position at absolute Y coordinate near bottom
            // A4 page height is 297mm, subtract bottom margin (50mm as set in constructor) and image heights
            $yPosition = 297 - 50 - 5; // 242mm from top, which is near the bottom
            
            $this->Image(IMAGES_PATH .'sello_cens.png', 85, $yPosition, 30, 40, 'PNG'); // Bottom Center
            $this->Image(IMAGES_PATH .'firma_director_luis.png', 120, $yPosition + 5, 60, 35, 'PNG'); // Bottom Right
            
            $this->SetAlpha(1); // Reset transparency
        }
}    
    
    