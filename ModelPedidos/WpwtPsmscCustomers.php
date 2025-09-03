<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscCustomers extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_customers";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $email = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $ticket_count = null;

    /** @var int|null */
    public ?int $user = null;

    /** @var int|null */
    public ?int $WpwtPsmscAgents_customer_Count = null;

    /** @var WpwtPsmscAgents[] (ref wpwt_psmsc_agents.customer _m:o wpwt_psmsc_customers.id) */
    public array $WpwtPsmscAgents_customer_ = [];

    /** @var int|null */
    public ?int $WpwtPsmscLogs_modified_by_Count = null;

    /** @var WpwtPsmscLogs[] (ref wpwt_psmsc_logs.modified_by _m:o wpwt_psmsc_customers.id) */
    public array $WpwtPsmscLogs_modified_by_ = [];

    /** @var int|null */
    public ?int $WpwtPsmscThreads_customer_Count = null;

    /** @var WpwtPsmscThreads[] (ref wpwt_psmsc_threads.customer _m:o wpwt_psmsc_customers.id) */
    public array $WpwtPsmscThreads_customer_ = [];

    /** @var int|null */
    public ?int $WpwtPsmscTickets_customer_Count = null;

    /** @var WpwtPsmscTickets[] (ref wpwt_psmsc_tickets.customer _m:o wpwt_psmsc_customers.id) */
    public array $WpwtPsmscTickets_customer_ = [];

}
