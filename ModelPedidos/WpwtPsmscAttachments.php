<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class WpwtPsmscAttachments extends Entity
{

    public function __construct()
    {
        $this->_entityName = "wpwt_psmsc_attachments";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $customer_id = null;

    /** @var DateTime|null */
    public ?DateTime $date_created = null;

    /** @var string|null */
    public ?string $file_path = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $is_active = null;

    /** @var int|null */
    public ?int $is_image = null;

    /** @var int|null */
    public ?int $is_uploaded = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var string|null */
    public ?string $source = null;

    /** @var int|null */
    public ?int $source_id = null;

    /** @var int|null */
    public ?int $ticket_id = null;

    /** @var WpwtPsmscTickets|null (fk wpwt_psmsc_attachments.ticket_id _m:o wpwt_psmsc_tickets.id) */
    public ?\Pedidos\WpwtPsmscTickets_ $ticket_id_ = null;

}
