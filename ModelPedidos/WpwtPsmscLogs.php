<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscLogs extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_logs";
        $this->_db = \SqlOrganize\Sql\DbMy::getInstance();
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

    /** @var WpwtPsmscCustomers|null (fk wpwt_psmsc_logs.modified_by _m:o wpwt_psmsc_customers.id) */
    public ?\Pedidos\WpwtPsmscCustomers_ $modified_by_ = null;

}
