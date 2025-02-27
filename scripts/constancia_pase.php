<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$pdo_pedidos = new PDO("mysql:host=$db_host_pedidos;dbname=$db_name_pedidos;charset=utf8mb4", $db_user_pedidos, $db_pass_pedidos, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);


$numero_documento = $_POST["numero_documento"];
$actual_year = date('Y');
$actual_month = date('m');
$actual_unix_timestamp = time();
$upload_dir = "/wpsc/{$actual_year}/{$actual_month}/";
$filename = "{$actual_unix_timestamp}_constancia_{$numero_documento}.pdf";
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
    "filename" => $filename,
    "upload_dir" => $upload_dir,
    "save_path" => $upload_dir."/".$filename
];


class ConstanciaPasePDF extends TCPDF {
    private $qrFile;

    public function setQRFile($qrFile) {
        $this->qrFile = $qrFile;
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

function generar_constancia_pase($url, $data) {
    global $rutaUploadPedidos;

    // Create QR Code
    $options = new QROptions([
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'scale' => 5,
    ]);

    $qrcode = (new QRCode($options))->render($url);
    $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

    // Create PDF instance
    $pdf = new ConstanciaPasePDF('P', 'mm', 'A4');
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Escuela CENS Nº 462');
    $pdf->SetTitle('Constancia de Alumno Regular');
    $pdf->SetMargins(20, 45, 20);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(true, 50);

    // Set QR Code for header
    $pdf->setQRFile($qrFile);

    $pdf->AddPage();

    // Title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, "CONSTANCIA DE PASE", 0, 1, 'C');
    $pdf->Ln(5);

    // Content
    $pdf->SetFont('helvetica', '', 10);
    $content = "<p>La Dirección de la Escuela de Educación CENS Nº 462 de La Plata, hace constar por la presente que
    <strong><u><i>{$data['apellidos']}, {$data['nombres']}</i></u></strong> DNI Nº <strong><u><i>{$data['numero_documento']}</i></u></strong> 
    ha cursado los años <strong><u><i>{$data['anios_cursados']}</i></u></strong>
    del <strong><u><i>Programa Fines 2 Trayecto Secundario</i></u></strong> con orientación en <strong><u><i>{$data['orientacion']}</i></u></strong>,
    resolución <strong><u><i>{$data['resolucion']}</i></u></strong>, bajo el siguiente detalle.</p>";

    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');
    $pdf->Ln(5);

    // Approved Grades Table
    if (!empty($data["calificaciones_aprobadas"])) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Calificaciones Aprobadas', 0, 1);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Tramo', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Nota', 1, 1, 'C', true);

        foreach ($data["calificaciones_aprobadas"] as $row) {
            $nota = (round($row->nota_final) != 0) ? round($row->nota_final) : round($row->crec)."c";
            $pdf->Cell(100, 8, $row->nombre, 1);
            $pdf->Cell(25, 8, $row->tramo, 1);
            $pdf->Cell(25, 8, $nota, 1, 1);
        }
        $pdf->Ln(5);
    }

    // Failed Grades Table
    if (!empty($data["calificaciones_desaprobadas"])) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Calificaciones Desaprobadas', 0, 1);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(100, 8, 'Asignatura', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Tramo', 1, 1, 'C', true);

        foreach ($data["calificaciones_desaprobadas"] as $row) {
            $pdf->Cell(100, 8, $row->nombre, 1);
            $pdf->Cell(25, 8, $row->tramo, 1, 1);
        }
    }

    $pdf->Ln(5);

    $content = "<p>Se extiende la presente a pedido del interesado en La Plata el día <strong><u><i>{$data['fecha']}</i></u></strong> para ser presentado ante <strong><u><i>{$data['presentacion']}</i></u></strong>.</p>";

    if (!empty($data['observaciones'])) {
        $content .= "<p>Observaciones:<strong><u><i>{$data['observaciones']}</i></u></strong></p>";
    }

    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');

    // Ensure directories exist
    if (!file_exists(dirname($rutaUploadPedidos . $data["upload_dir"]))) {
        mkdir(dirname($rutaUploadPedidos . $data["upload_dir"]), 0777, true);
    }

    // Save the PDF
    $pdf->Output($rutaUploadPedidos . $data["save_path"], "F"); // Save to file
    $pdf->Output($data["filename"], "I"); // Display in browser

    // Clean up temp QR file
    unlink($qrFile);
}


function getNextId($pdo, $table) {
    $stmt = $pdo->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM $table");
    return $stmt->fetchColumn();
}

function getNextFieldName($pdo, $table, $fieldName) {
    $stmt = $pdo->query("SELECT IFNULL(MAX($fieldName), 0) + 1 AS next_id FROM $table");
    return $stmt->fetchColumn();
}

function generateAuthCode($length = 8) {
    return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
}

function insertar_pedido($data){
    global $pdo_pedidos;
    
    $ticket_id = getNextId($pdo_pedidos, 'wpwt_psmsc_tickets');
    $thread_id = getNextId($pdo_pedidos, 'wpwt_psmsc_threads');  
    $attachment_id = getNextId($pdo_pedidos, 'wpwt_psmsc_attachments')  ;
    $auth_code = generateAuthCode();

    // Insert into wpwt_psmsc_tickets
    $sql_ticket = "INSERT INTO wpwt_psmsc_tickets 
        (   
            id, 
            is_active, 
            customer, 
            subject, 
            status, 
            priority, 
            category, 
            assigned_agent, 
            date_created, 
            date_updated, 
            agent_created, 
            ip_address, 
            source,
            browser, 
            os, 
            add_recipients, 
            prev_assignee, 
            date_closed, 
            user_type, 
            last_reply_on, 
            last_reply_by,
            auth_code,
            cust_24,
            cust_25,
            cust_26,
            cust_27,
            cust_28,
            tags,
            last_reply_source,
            misc
        )
        VALUES (
            :id, 
            :is_active, 
            :customer, 
            :subject, 
            :status, 
            :priority, 
            :category, 
            :assigned_agent, 
            :date_created, 
            :date_updated, 
            :agent_created, 
            :ip_address, 
            :source, 
            :browser, 
            :os, 
            :add_recipients, 
            :prev_assignee, 
            :date_closed, 
            :user_type, 
            :last_reply_on, 
            :last_reply_by,
            :auth_code,
            :cust_24,
            :cust_25,
            :cust_26,
            :cust_27,
            :cust_28,
            :tags,
            :last_reply_source,
            :misc
        )";

    $stmt_ticket = $pdo_pedidos->prepare($sql_ticket);
    $stmt_ticket->execute([
        ':id' => $ticket_id,
        ':is_active' => 1,
        ':customer' => 450, //corresponde a sistemas
        ':subject' => "Constancia de pase : " . $data["apellidos"] . ", " . $data["nombres"], //titulo
        ':status' => 4, //cerrado
        ':priority' => 1, //baja 
        ':category' => 10, //constancia
        ':assigned_agent' => "", //cadena vacia
        ':date_created' => date('Y-m-d H:i:s'),
        ':date_updated' => date('Y-m-d H:i:s'),
        ':agent_created' => 0,
        ':ip_address' => "",
        ':source' => "",
        ':browser' => "",
        ':os' => "",
        ':add_recipients' => "",
        ':prev_assignee' => "",
        ':date_closed' => date('Y-m-d H:i:s'), //completar solo si status es 4
        ':user_type' => 'registered',
        ':last_reply_on' => "0000-00-00 00:00:00",
        ':last_reply_by' => "450",
        ':auth_code' => $auth_code,
        ':cust_24' => $data["numero_documento"], //tinytext
        ':cust_25' => "", //tinytext
        ':cust_26' => "", //tinytext 
        ':cust_27' => "", //tinytext, telefono 
        ':cust_28' => "Válido por 30 días", //tinytext, comentario
        ':tags' => "", //tinytext
        //':live_agents' => '{"1":"2024-12-25 14:11:12"}', //tinytext
        'last_reply_source'=> "", //varchar(50)
        'misc'=> "", //longtext
    ]);

    $threads_body = "La Dirección de la escuela CENS 462 de La Plata, hace constar por la presente que ";
    $threads_body .= $data['apellidos'] .    ", ";
    $threads_body .= $data['nombres'] . " DNI N° ";
    $threads_body .= $data['numero_documento'] . " ha cursado los años ";
    $threads_body .= $data['anios_cursados'] . " del Programa Fines 2 Trayecto Secundario con orientación en ";
    $threads_body .= $data['orientacion'] . " resolución ";
    $threads_body .= $data['resolucion'] . ", aprobando " . count($data['calificaciones_aprobadas']) . " y adeudando " . count($data['calificaciones_desaprobadas']) . " materias.";
    
    if (!empty($data['observaciones'])) {
        $threads_body .= " - " . $data['observaciones']; 
    }
    
    // Function to convert a number to an ordinal string in Spanish
    function toOrdinalSpanish($number) {
        $ordinals = [
            1 => 'primer', 2 => 'segundo', 3 => 'tercer', 4 => 'cuarto',
            5 => 'quinto', 6 => 'sexto', 7 => 'séptimo', 8 => 'octavo',
            9 => 'noveno', 10 => 'décimo'
        ];
        return $ordinals[$number] ?? $number . "º";
    }
    
    // Insert into wpwt_psmsc_threads
    $sql_thread = "INSERT INTO wpwt_psmsc_threads 
        (
            id, 
            ticket, 
            is_active, 
            customer, 
            type, 
            body, 
            attachments, 
            ip_address, 
            source, 
            os, 
            browser, 
            seen, 
            date_created, 
            date_updated
        ) 
        VALUES 
        (
            :id, 
            :ticket, 
            :is_active, 
            :customer, 
            :type, 
            :body, 
            :attachments, 
            :ip_address, 
            :source, 
            :os, 
            :browser, 
            :seen, 
            :date_created, 
            :date_updated
        )";

    $stmt_thread = $pdo_pedidos->prepare($sql_thread);
    $stmt_thread->execute([
        ':id' => $thread_id,
        ':ticket' => $ticket_id,
        ':is_active' => 1,
        ':customer' => 450, //corresponde a sistemas
        ':type' => "report",
        ':body' => $threads_body,
        ':attachments' => $attachment_id, 
        ':ip_address' => "",
        ':source' => "",
        ':os' => "",
        ':browser' => "",
        ':seen' => "",
        ':date_created' => date('Y-m-d H:i:s'),
        ':date_updated' => date('Y-m-d H:i:s')
    ]);    

    // Get the next available ID
    // Insert into wpwt_psmsc_attachments
    $sql_attachment = "INSERT INTO wpwt_psmsc_attachments 
        (id, name, file_path, is_image, is_active, is_uploaded, date_created, source, source_id, ticket_id, customer_id) 
        VALUES 
        (:id, :name, :file_path, :is_image, :is_active, :is_uploaded, :date_created, :source, :source_id, :ticket_id, :customer_id)";

    $stmt_attachment = $pdo_pedidos->prepare($sql_attachment);
    $stmt_attachment->execute([
        ':id' => $attachment_id,
        ':name' => $data['filename'],
        ':file_path' => $data['save_path'],
        ':is_image' => 1,
        ':is_active' => 1, // Mark as active
        ':is_uploaded' => 0, // Mark as uploaded
        ':date_created' => date('Y-m-d H:i:s'),
        ':source' => "report",
        ':source_id' => $thread_id,
        ':ticket_id' => $ticket_id,
        ':customer_id' => 0
    ]);

    return "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket_id . "&auth-code=" . $auth_code;
}


// Call the function
try {
    $url = insertar_pedido($data);
    generar_constancia_pase($url, $data);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>