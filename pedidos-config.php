<?php


require_once __DIR__ . '/main-config.php';

require_once MAIN_PATH . 'schema-pedidos.php';
require_once MAIN_PATH . 'schema-pedidos_.php';
require_once MAIN_PATH . 'DataAccessPedidos/AttachmentsDAO.php';

\App\Context::initPedidosDb();