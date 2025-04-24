<?php

/**
 * @example
 * $pdoFines = new PdoFines($db_host, $db_name, $db_user, $db_pass);
 * $pdoFines->pdo_fines->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 * $pdoFines->pdo_fines->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
 */
class PdoPedidos
{
    
        public $pdo;
    
        public function __construct()
        {
            try {
                $this->pdo = new PDO("mysql:host=" . DB_HOST_PEDIDOS . ";dbname=" . DB_NAME_PEDIDOS, DB_USER_PEDIDOS, DB_PASS_PEDIDOS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                $this->pdo->exec("SET NAMES 'utf8mb3'");

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
 
        function getNextId($table) {
            $stmt = $this->pdo->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM $table");
            return $stmt->fetchColumn();
        }

        function getNextFieldName($table, $fieldName) {
            $stmt = $this->pdo->query("SELECT IFNULL(MAX($fieldName), 0) + 1 AS next_id FROM $table");
            return $stmt->fetchColumn();
        }

        protected function generateAuthCode($length = 8) {
            return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
        }

      
        public function insertarPedidoYDefinirDatosArchivo($data){
            $numero_documento = $data["numero_documento"];
            $actual_year = date('Y');
            $actual_month = date('m');
            $actual_unix_timestamp = time();
            $upload_dir = "/wpsc/{$actual_year}/{$actual_month}/";
            $filename = "{$actual_unix_timestamp}_{$numero_documento}.pdf";

            $data["filename"] = $filename;
            $data["upload_dir"] = $upload_dir;
            $data["save_path"] = $upload_dir."/".$filename;
            
            $ticket_id = $this->getNextId('wpwt_psmsc_tickets');
            $thread_id = $this->getNextId('wpwt_psmsc_threads');  
            $attachment_id = $this->getNextId('wpwt_psmsc_attachments')  ;
            $auth_code = $this->generateAuthCode();
        
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
        
            $stmt_ticket = $this->pdo->prepare($sql_ticket);
            $stmt_ticket->execute([
                ':id' => $ticket_id,
                ':is_active' => 1,
                ':customer' => 450, //corresponde a sistemas
                ':subject' => $data["titulo"] . " : " . $data["apellidos"] . ", " . $data["nombres"], //titulo
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
        
            $stmt_thread = $this->pdo->prepare($sql_thread);
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
        
            $stmt_attachment = $this->pdo->prepare($sql_attachment);
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
        
            $data["url"] = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket_id . "&auth-code=" . $auth_code;
            return $data;
        }
}
