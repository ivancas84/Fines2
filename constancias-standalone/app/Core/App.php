<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

use Dotenv\Dotenv;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class App
{
    private array $routes = [];

    public function __construct(
        private readonly string $basePath,
        private readonly Config $config,
        private readonly Database $database,
        private readonly View $view,
        private readonly Auth $auth,
        private readonly Csrf $csrf,
    ) {
    }

    public static function boot(string $basePath): self
    {
        if (is_file($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->safeLoad();
        }

        $config = new Config($basePath);
        Session::start($config->string('SESSION_NAME', 'constancias_app_session'));
        $database = new Database($config);
        $csrf = new Csrf();
        $auth = new Auth($database->pdo());
        $view = new View($basePath . '/resources/views', $config, $auth, $csrf);

        return new self($basePath, $config, $database, $view, $auth, $csrf);
    }

    public function get(string $path, array $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, array $handler): void
    {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function dispatch(Request $request): void
    {
        try {
            $dispatcher = simpleDispatcher(function (RouteCollector $router): void {
                foreach ($this->routes as [$method, $path, $handler]) {
                    $router->addRoute($method, $path, $handler);
                }
            });

            $routeInfo = $dispatcher->dispatch($request->method(), $request->path($this->config->basePath()));
            if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
                $this->view->render('errors/404', ['title' => 'Pagina no encontrada'], 404);
                return;
            }
            if ($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
                Response::text('Metodo no permitido', 405);
            }

            [, $handler, $vars] = $routeInfo;
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass($this->database->pdo(), $this->view, $this->auth, $this->csrf, $this->config);
            $controller->{$method}($request, $vars);
        } catch (\Throwable $throwable) {
            if ($this->config->bool('APP_DEBUG', false)) {
                Response::text($throwable->getMessage(), 500);
            }

            $this->view->render('errors/500', ['title' => 'Error interno'], 500);
        }
    }
}
