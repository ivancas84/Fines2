<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Agents extends Entity
{

    public function __construct()
    {
        $this->_entityName = "agents";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $customer = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $is_active = null;

    /** @var int|null */
    public ?int $is_agentgroup = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $role = null;

    /** @var int|null */
    public ?int $unresolved_count = null;

    /** @var int|null */
    public ?int $user = null;

    /** @var int|null */
    public ?int $workload = null;

    /** @var Customers|null (fk agents.customer _m:o customers.id) */
    public ?\Pedidos\Customers_ $customer_ = null;

}
