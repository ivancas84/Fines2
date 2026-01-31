<?php

namespace Pedidos\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class TicketTags extends Entity
{

    public function __construct()
    {
        $this->_entityName = "ticket_tags";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var string|null */
    public ?string $bg_color = null;

    /** @var string|null */
    public ?string $color = null;

    /** @var string|null */
    public ?string $description = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var string|null */
    public ?string $name = null;

}
