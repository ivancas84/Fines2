<?php

namespace Pedidos;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class CustomFields extends Entity
{

    public function __construct()
    {
        $this->_entityName = "custom_fields";
        $this->_db = \App\Context::getPedidosDb();
        $this->setDefault();
    }

    /** @var int|null */
    public ?int $allow_my_profile = null;

    /** @var int|null */
    public ?int $allow_ticket_form = null;

    /** @var int|null */
    public ?int $char_limit = null;

    /** @var string|null */
    public ?string $date_display_as = null;

    /** @var string|null */
    public ?string $date_format = null;

    /** @var string|null */
    public ?string $date_range = null;

    /** @var string|null */
    public ?string $default_value = null;

    /** @var DateTime|null */
    public ?DateTime $end_range = null;

    /** @var string|null */
    public ?string $extra_info = null;

    /** @var string|null */
    public ?string $field = null;

    /** @var int|null */
    public ?int $id = null;

    /** @var int|null */
    public ?int $is_auto_fill = null;

    /** @var int|null */
    public ?int $is_personal_info = null;

    /** @var int|null */
    public ?int $load_order = null;

    /** @var string|null */
    public ?string $name = null;

    /** @var string|null */
    public ?string $number_type = null;

    /** @var string|null */
    public ?string $placeholder_text = null;

    /** @var string|null */
    public ?string $slug = null;

    /** @var DateTime|null */
    public ?DateTime $start_range = null;

    /** @var int|null */
    public ?int $time_format = null;

    /** @var int|null */
    public ?int $tl_width = null;

    /** @var string|null */
    public ?string $type = null;

}
