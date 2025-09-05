<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Threads extends Entity
{

    public function __construct()
    {
        $this->_entityName = "threads";
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

    /** @var Customers|null (fk threads.customer _m:o customers.id) */
    public ?\Pedidos\Customers_ $customer_ = null;

    /** @var Tickets|null (fk threads.ticket _m:o tickets.id) */
    public ?\Pedidos\Tickets_ $ticket_ = null;

}
