<?php


require_once __DIR__ . '/main-config.php';

require_once MAIN_PATH . 'schema-pedidos.php';
require_once MAIN_PATH . 'schema-pedidos_.php';
require_once MAIN_PATH . 'DataAccessPedidos/AttachmentsDAO.php';
require_once MAIN_PATH . 'DataAccessPedidos/TicketsDAO.php';
require_once MAIN_PATH . 'Pdf/PDFConstanciaPase.php';


\App\Context::initPedidosDb();