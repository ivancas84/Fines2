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
            try {
                Dotenv::createImmutable($basePath)->safeLoad();
            } catch (\Throwable $throwable) {
                error_log('No se pudo leer .env de constancias: ' . $throwable->getMessage());
            }
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

            $requestPath = $request->path($this->config->basePath());
            $routeInfo = $dispatcher->dispatch($request->method(), $requestPath);
            if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
                $debug = $request->query('debug_rutas') === '1' || $this->config->bool('APP_DEBUG', false);
                $this->view->render('errors/404', [
                    'title' => 'Pagina no encontrada',
                    'debug' => $debug ? [
                        'method' => $request->method(),
                        'request_uri' => (string) ($_SERVER['REQUEST_URI'] ?? ''),
                        'script_name' => (string) ($_SERVER['SCRIPT_NAME'] ?? ''),
                        'script_filename' => (string) ($_SERVER['SCRIPT_FILENAME'] ?? ''),
                        'app_base_path' => $this->config->basePath(),
                        'path' => $requestPath,
                        'routes' => array_map(
                            static fn (array $route): string => $route[0] . ' ' . $route[1],
                            $this->routes,
                        ),
                    ] : null,
                ], 404);
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
