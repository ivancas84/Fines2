<?php

namespace Pedidos\Model;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Categories extends Entity
{

    public function __construct()
    {
        $this->_entityName = "categories";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $load_order = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var int|null */
    public ?int $Tickets_category_Count = null;

    /** @var Tickets[] (ref tickets.category _m:o categories.id) */
    public array $Tickets_category_ = [];

}
