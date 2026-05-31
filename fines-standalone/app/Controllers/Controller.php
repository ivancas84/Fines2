<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Auth;
use FinesApp\Core\Csrf;
use FinesApp\Core\View;
use PDO;

abstract class Controller
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly View $view,
        protected readonly Auth $auth,
        protected readonly Csrf $csrf,
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
