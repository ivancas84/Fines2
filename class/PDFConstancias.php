<?php

require_once './includes/db_config.php';
require_once './vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
    
    class PDFConstancias extends TCPDF {
    
        protected $qrFile;
        protected $data = array();

        

        protected function qrFile($url) {
            // Generate QR code
            $options = new QROptions([
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'scale' => 5,
            ]);
    
            $qrcode = (new QRCode($options))->render($url);
    
            $this->qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
            file_put_contents($this->qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));
        }



        protected function mainDataA5($title, $url){
            $this->qrFile($url);  
            $this->SetCreator(PDF_CREATOR);
            $this->SetAuthor('Escuela CENS Nº 462');
            $this->SetTitle($title);
            $this->SetMargins(20, 30, 20);
            $this->setPrintHeader(false);
            $this->AddPage('L', 'A5');
            $this->Rect(10, 10, 190, 125);
    
            // Header images
            $this->Image('./images/logo.jpg', 20, 15, 120, 0, 'JPG');
            $this->Image($this->qrFile, 160, 15, 30, 30, 'PNG');
            $this->Ln(15);
            $this->SetAlpha(0.9);
            $this->Image('./images/sello_cens.png', 85, 85, 30, 40, 'PNG');
            $this->Image('./images/firma_director_luis.png', 120, 90, 60, 35, 'PNG');
            $this->SetAlpha(1);
    
            // Title
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 10, $title, 0, 1, 'C');
            $this->Ln(5);

            $this->SetFont('helvetica', '', 10);
        }

        public function OutputBase($uploadDir, $fileName){
            $dir = PATH_UPLOAD_PEDIDOS . $uploadDir;
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    throw new Exception("Failed to create directory: $dir");
                }
            }

            $this->Output($dir . $fileName, "F");
            $this->Output($fileName, "I");

            unlink($this->qrFile);
        }

        public function OutputData($data){
            $this->mainDataA5($data["titulo"], $data["url"]);
            $this->writeHTMLCell(0, 0, '', '', $data["body"], 0, 1, false, true, 'J');

            $this->OutputBase($data["upload_dir"], $data["filename"]);
        }

        public function constanciaGeneral($data){
            // Content
            $content = "
            <p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['apellidos']}, {$data['nombres']}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['numero_documento']}&nbsp;&nbsp;&nbsp;</i></u></strong>:</p>
            <p><strong><u><i>&nbsp;&nbsp;&nbsp;{$data['contenido']}&nbsp;&nbsp;&nbsp;</i></u></strong></p> 
            <p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['fecha']}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['presentacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
            ";
    
            if (!empty($data['observaciones'])) {
                $content .= "<p>Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$data['observaciones']}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
            }
    
    
            return $content;
        }

        public function constanciaVacante($data){
            // Content
            $content = "
            <p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['apellidos']}, {$data['nombres']}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['numero_documento']}&nbsp;&nbsp;&nbsp;</i></u></strong> 
            tiene una vacante en este establecimiento.</p>
            <p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['fecha']}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['presentacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
            ";
    
            if (!empty($data['observaciones'])) {
                $content .= "<p>Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$data['observaciones']}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
            }
            
            return $content;
        }

        
        public function constanciaAlumnoRegular($data)
        {
            $content = "
            <p>La Dirección del CENS Nº 462 de La Plata, hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['apellidos']}, {$data['nombres']}&nbsp;&nbsp;&nbsp;</i></u></strong> DNI Nº <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['numero_documento']}&nbsp;&nbsp;&nbsp;</i></u></strong> 
            es alumno/a regular de <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['anio']}&nbsp;&nbsp;&nbsp;</i></u></strong> año <strong><u><i>&nbsp;&nbsp;&nbsp;Programa Fines 2 Trayecto Secundario&nbsp;&nbsp;&nbsp;</i></u></strong> con orientación en <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['orientacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>
            resolución <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['resolucion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
            <p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['fecha']}&nbsp;&nbsp;&nbsp;</i></u></strong> para ser presentado ante <strong><u><i>&nbsp;&nbsp;&nbsp;{$data['presentacion']}&nbsp;&nbsp;&nbsp;</i></u></strong>.</p>
            ";
            
            if (!empty($data['observaciones'])) {
                $content .= "<p>Observaciones:<strong><u><i>&nbsp;&nbsp;&nbsp;{$data['observaciones']}&nbsp;&nbsp;&nbsp;</i></u></strong></p>";
            }
        
            return $content;    
            
        }
    }    
    
    