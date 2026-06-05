<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Auth;
use ConstanciasApp\Core\Config;
use ConstanciasApp\Core\Csrf;
use ConstanciasApp\Core\View;
use PDO;

abstract class Controller
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly View $view,
        protected readonly Auth $auth,
        protected readonly Csrf $csrf,
        protected readonly Config $config,
    ) {
    }

    protected function requireLogin(): void
    {
        $this->auth->requireLogin();
    }

    protected function requireEdit(): void
    {
        $this->requireLogin();
        if (!$this->auth->canEdit()) {
            throw new \RuntimeException('No tenes permisos para modificar datos.');
        }
    }
}
