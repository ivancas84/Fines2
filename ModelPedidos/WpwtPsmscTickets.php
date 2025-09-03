<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscTickets extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_tickets";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $add_recipients = null;

    /** @var int|null */
    public ?int $agent_created = null;

    /** @var string|null */
    public ?string $assigned_agent = null;

    /** @var string|null */
    public ?string $auth_code = null;

    /** @var string|null */
    public ?string $browser = null;

    /** @var int|null */
    public ?int $category = null;

    /** @var int|null */
    public ?int $customer = null;

    /** @var tinytext|null */
    public ?tinytext $cust_24 = null;

    /** @var tinytext|null */
    public ?tinytext $cust_25 = null;

    /** @var tinytext|null */
    public ?tinytext $cust_26 = null;

    /** @var tinytext|null */
    public ?tinytext $cust_27 = null;

    /** @var tinytext|null */
    public ?tinytext $cust_28 = null;

    /** @var DateTime|null */
    public ?DateTime $date_closed = null;

    /** @var DateTime|null */
    public ?DateTime $date_created = null;

    /** @var DateTime|null */
    public ?DateTime $date_updated = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var string|null */
    public ?string $ip_address = null;

    /** @var int|null */
    public ?int $is_active = null;

    /** @var int|null */
    public ?int $last_reply_by = null;

    /** @var DateTime|null */
    public ?DateTime $last_reply_on = null;

    /** @var string|null */
    public ?string $last_reply_source = null;

    /** @var tinytext|null */
    public ?tinytext $live_agents = null;

    /** @var longtext|null */
    public ?longtext $misc = null;

    /** @var string|null */
    public ?string $os = null;

    /** @var string|null */
    public ?string $prev_assignee = null;

    /** @var int|null */
    public ?int $priority = null;

    /** @var string|null */
    public ?string $source = null;

    /** @var int|null */
    public ?int $status = null;

    /** @var string|null */
    public ?string $subject = null;

    /** @var tinytext|null */
    public ?tinytext $tags = null;

    /** @var string|null */
    public ?string $user_type = null;

    /** @var WpwtPsmscCategories|null (fk wpwt_psmsc_tickets.category _m:o wpwt_psmsc_categories.id) */
    public ?\Pedidos\WpwtPsmscCategories_ $category_ = null;

    /** @var WpwtPsmscCustomers|null (fk wpwt_psmsc_tickets.customer _m:o wpwt_psmsc_customers.id) */
    public ?\Pedidos\WpwtPsmscCustomers_ $customer_ = null;

    /** @var WpwtPsmscPriorities|null (fk wpwt_psmsc_tickets.priority _m:o wpwt_psmsc_priorities.id) */
    public ?\Pedidos\WpwtPsmscPriorities_ $priority_ = null;

    /** @var WpwtPsmscStatuses|null (fk wpwt_psmsc_tickets.status _m:o wpwt_psmsc_statuses.id) */
    public ?\Pedidos\WpwtPsmscStatuses_ $status_ = null;

    /** @var int|null */
    public ?int $WpwtPsmscAttachments_ticket_id_Count = null;

    /** @var WpwtPsmscAttachments[] (ref wpwt_psmsc_attachments.ticket_id _m:o wpwt_psmsc_tickets.id) */
    public array $WpwtPsmscAttachments_ticket_id_ = [];

    /** @var int|null */
    public ?int $WpwtPsmscThreads_ticket_Count = null;

    /** @var WpwtPsmscThreads[] (ref wpwt_psmsc_threads.ticket _m:o wpwt_psmsc_tickets.id) */
    public array $WpwtPsmscThreads_ticket_ = [];

}
