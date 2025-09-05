<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Logs extends Entity
{

    public function __construct()
    {
        $this->_entityName = "logs";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var longtext|null */
    public ?longtext $body = null;

    /** @var DateTime|null */
    public ?DateTime $date_created = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $modified_by = null;

    /** @var int|null */
    public ?int $ref_id = null;

    /** @var string|null */
    public ?string $type = null;

    /** @var Customers|null (fk logs.modified_by _m:o customers.id) */
    public ?\Pedidos\Customers_ $modified_by_ = null;

}
