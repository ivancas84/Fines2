<?php

function generateAuthCode($length = 8) {
    return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
}

function getNextId($pdo, $table) {
    $stmt = $pdo->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM $table");
    return $stmt->fetchColumn();
}

function getNextFieldName($pdo, $table, $fieldName) {
    $stmt = $pdo->query("SELECT IFNULL(MAX($fieldName), 0) + 1 AS next_id FROM $table");
    return $stmt->fetchColumn();
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

function pdoInsertarPedido($data){
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
        ':body' => $data["body"],
        ':attachments' => $attachment_id, 
        ':ip_address' => "",
        ':source' => "",
        ':os' => "",
        ':browser' => "",
        ':seen' => null,
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

    return "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket_id . "&auth-code=" . $auth_code;}