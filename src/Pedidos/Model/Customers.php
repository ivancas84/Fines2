<?php

namespace Pedidos\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Customers extends Entity
{

    public function __construct()
    {
        $this->_entityName = "customers";
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
    public ?int $Agents_customer_Count = null;

    /** @var Agents[] (ref agents.customer _m:o customers.id) */
    public array $Agents_customer_ = [];

    /** @var int|null */
    public ?int $Logs_modified_by_Count = null;

    /** @var Logs[] (ref logs.modified_by _m:o customers.id) */
    public array $Logs_modified_by_ = [];

    /** @var int|null */
    public ?int $Threads_customer_Count = null;

    /** @var Threads[] (ref threads.customer _m:o customers.id) */
    public array $Threads_customer_ = [];

    /** @var int|null */
    public ?int $Tickets_customer_Count = null;

    /** @var Tickets[] (ref tickets.customer _m:o customers.id) */
    public array $Tickets_customer_ = [];

}
