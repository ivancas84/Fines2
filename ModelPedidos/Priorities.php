<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Priorities extends Entity
{

    public function __construct()
    {
        $this->_entityName = "priorities";
        $this->_db = \App\Context::getPedidosDb();
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
    public ?int $Tickets_priority_Count = null;

    /** @var Tickets[] (ref tickets.priority _m:o priorities.id) */
    public array $Tickets_priority_ = [];

}
