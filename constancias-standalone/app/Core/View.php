<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

final class View
{
    public function __construct(
        private readonly string $viewPath,
        private readonly Config $config,
        private readonly Auth $auth,
        private readonly Csrf $csrf,
    ) {
    }

    public function render(string $view, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        echo $this->renderToString($view, $data);
    }

    public function renderToString(string $view, array $data = []): string
    {
        $shared = [
            'auth' => $this->auth,
            'csrf' => $this->csrf,
            'config' => $this->config,
        ];
        $content = $this->renderFile($view, array_merge($data, $shared));

        return $this->renderFile('layouts/app', array_merge($data, $shared, [
            'content' => $content,
        ]));
    }

    private function renderFile(string $view, array $data): string
    {
        $file = $this->viewPath . '/' . $view . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException('Vista no encontrada: ' . $view);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
