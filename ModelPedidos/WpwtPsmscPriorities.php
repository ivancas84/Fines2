<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscPriorities extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_priorities";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $bg_color = null;

    /** @var string|null */
    public ?string $color = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $load_order = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $WpwtPsmscTickets_priority_Count = null;

    /** @var WpwtPsmscTickets[] (ref wpwt_psmsc_tickets.priority _m:o wpwt_psmsc_priorities.id) */
    public array $WpwtPsmscTickets_priority_ = [];

}
