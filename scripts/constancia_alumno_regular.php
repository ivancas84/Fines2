<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'db_config.php';
require_once 'vendor/autoload.php'; // Ensure TCPDF is autoloaded

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $pdo_pedidos = new PDO("mysql:host=$db_host_pedidos;dbname=$db_name_pedidos;charset=utf8mb4", $db_user_pedidos, $db_pass_pedidos, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$numero_documento = filter_input(INPUT_POST, 'numero_documento', FILTER_SANITIZE_STRING);
$actual_unix_timestamp = time();
$upload_dir = "/wpsc/". date('Y') . "/" . date('m') . "/";
$filename = "{$actual_unix_timestamp}_constancia_{$numero_documento}.pdf";
$data = [
    "apellidos" => strtoupper(filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING)),
    "nombres" => ucwords(strtolower(filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_STRING))),
    "numero_documento" => $numero_documento ,
    "anio" => filter_input(INPUT_POST, 'anio_en_curso', FILTER_SANITIZE_STRING),
    "resolucion" => filter_input(INPUT_POST, 'resolucion', FILTER_SANITIZE_STRING),
    "fecha" => filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING),
    "presentacion" => filter_input(INPUT_POST, 'presentado', FILTER_SANITIZE_STRING),
    "orientacion" => filter_input(INPUT_POST, 'orientacion', FILTER_SANITIZE_STRING),
    "observaciones" => filter_input(INPUT_POST, 'observaciones', FILTER_SANITIZE_STRING),
    "filename" => $filename,
    "upload_dir" => $upload_dir,
    "save_path" => $upload_dir."/".$filename
];


function generar_constancia_alumno_regular($url, $data) {
    global $rutaUploadPedidos;
    // Create QR Code

    $options = new QROptions([
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'scale' => 5,
    ]);

    $qrcode = (new QRCode($options))->render($url);

    // Save the QR Code as a temporary file
    $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

    // Create PDF instance
    $pdf = new TCPDF('L', 'mm', 'A5'); // 'L' for Landscape, 'A5' for A5 paper size
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Escuela CENS Nº 462');
    $pdf->SetTitle('Constancia de Alumno Regular');
    $pdf->SetMargins(20, 30, 20);
    $pdf->setPrintHeader(false); // Avoid header line
    $pdf->AddPage();
    $pdf->Rect(10, 10, 190, 125); // Full-page border

    // Header with logo and QR code
    $pdf->Image('./images/logo.jpg', 20, 15, 120, 0, 'JPG'); // Logo occupies 2/3
    $pdf->Image($qrFile, 160, 15, 30, 30, 'PNG'); // QR occupies 1/3
    $pdf->Ln(15);
    $pdf->SetAlpha(0.9);

    // Add images at the bottom
    $pdf->Image('./images/sello_cens.png', 85, 85, 30, 40, 'PNG'); // Bottom Center
    $pdf->Image('./images/firma_director_luis.png', 120, 90, 60, 35, 'PNG'); // Bottom Right

    $pdf->SetAlpha(1); // Reset transparency
    // Title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, "CONSTANCIA DE ALUMNO REGULAR", 0, 1, 'C');
    $pdf->Ln(5);

    // Justify the content with interlineado
    $pdf->SetFont('helvetica', '', 10);
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

    // Write the content with justified text and line spacing
    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, false, true, 'J');

    

    // Ensure directories exist
    if (!file_exists(dirname($rutaUploadPedidos.$data["upload_dir"]))) {
        mkdir(dirname($rutaUploadPedidos.$data["upload_dir"]), 0777, true);
    }

    // Save the PDF
    $pdf->Output($rutaUploadPedidos.$data["save_path"], "F"); // Save to file
    // Output PDF
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
        ':subject' => "Constancia de alumno regular : " . $data["apellidos"] . ", " . $data["nombres"], //titulo
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

    $threads_body = "La Dirección del CENS 462 de La Plata, hace constar por la presente que ";
    $threads_body .= $data['apellidos'] . ", ";
    $threads_body .= $data['nombres'] . " DNI N° ";
    $threads_body .= $data['numero_documento'] . " es alumno regular de ";
    $threads_body .= $data['anio'] . " año Programa Fines 2 Trayecto Secundario con orientación en ";
    $threads_body .= $data['orientacion'] . " resolución ";
    $threads_body .= $data['resolucion'];
    
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
    generar_constancia_alumno_regular($url, $data);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



?>
