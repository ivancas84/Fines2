<?php

namespace Pedidos\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Statuses extends Entity
{

    public function __construct()
    {
        $this->_entityName = "statuses";
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
    public ?int $Tickets_status_Count = null;

    /** @var Tickets[] (ref tickets.status _m:o statuses.id) */
    public array $Tickets_status_ = [];

}
