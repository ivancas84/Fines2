<?php

namespace Pedidos\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Tickets extends Entity
{

    public function __construct()
    {
        $this->_entityName = "tickets";
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

    /** @var string|null */
    public ?string $cust_24 = null;

    /** @var string|null */
    public ?string $cust_25 = null;

    /** @var string|null */
    public ?string $cust_26 = null;

    /** @var string|null */
    public ?string $cust_27 = null;

    /** @var string|null */
    public ?string $cust_28 = null;

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

    /** @var string|null */
    public ?string $live_agents = null;

    /** @var string|null */
    public ?string $misc = null;

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

    /** @var string|null */
    public ?string $tags = null;

    /** @var string|null */
    public ?string $user_type = null;

    /** @var Categories|null (fk tickets.category _m:o categories.id) */
    public ?Categories_ $category_ = null;

    /** @var Customers|null (fk tickets.customer _m:o customers.id) */
    public ?Customers_ $customer_ = null;

    /** @var Priorities|null (fk tickets.priority _m:o priorities.id) */
    public ?Priorities_ $priority_ = null;

    /** @var Statuses|null (fk tickets.status _m:o statuses.id) */
    public ?Statuses_ $status_ = null;

    /** @var int|null */
    public ?int $Attachments_ticket_id_Count = null;

    /** @var Attachments[] (ref attachments.ticket_id _m:o tickets.id) */
    public array $Attachments_ticket_id_ = [];

    /** @var int|null */
    public ?int $Threads_ticket_Count = null;

    /** @var Threads[] (ref threads.ticket _m:o tickets.id) */
    public array $Threads_ticket_ = [];

}
