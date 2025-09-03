<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscThreads extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_threads";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $attachments = null;

    /** @var longtext|null */
    public ?longtext $body = null;

    /** @var string|null */
    public ?string $browser = null;

    /** @var int|null */
    public ?int $customer = null;

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

    /** @var string|null */
    public ?string $os = null;

    /** @var DateTime|null */
    public ?DateTime $seen = null;

    /** @var string|null */
    public ?string $source = null;

    /** @var int|null */
    public ?int $ticket = null;

    /** @var string|null */
    public ?string $type = null;

    /** @var WpwtPsmscCustomers|null (fk wpwt_psmsc_threads.customer _m:o wpwt_psmsc_customers.id) */
    public ?\Pedidos\WpwtPsmscCustomers_ $customer_ = null;

    /** @var WpwtPsmscTickets|null (fk wpwt_psmsc_threads.ticket _m:o wpwt_psmsc_tickets.id) */
    public ?\Pedidos\WpwtPsmscTickets_ $ticket_ = null;

}
