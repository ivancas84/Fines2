<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscTicketTags extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_ticket_tags";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $bg_color = null;

    /** @var string|null */
    public ?string $color = null;

    /** @var tinytext|null */
    public ?tinytext $description = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var string|null */
    public ?string $name = null;

}
