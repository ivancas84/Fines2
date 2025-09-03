<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscCategories extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_categories";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $load_order = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $WpwtPsmscTickets_category_Count = null;

    /** @var WpwtPsmscTickets[] (ref wpwt_psmsc_tickets.category _m:o wpwt_psmsc_categories.id) */
    public array $WpwtPsmscTickets_category_ = [];

}
