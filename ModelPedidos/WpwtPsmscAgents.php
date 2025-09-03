<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscAgents extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_agents";
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

    /** @var WpwtPsmscCustomers|null (fk wpwt_psmsc_agents.customer _m:o wpwt_psmsc_customers.id) */
    public ?\Pedidos\WpwtPsmscCustomers_ $customer_ = null;

}
