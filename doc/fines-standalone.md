## Flujo principal

### index.php
	
#### $app = App -> boot define una instancia de App con
    private readonly string $basePath,
    private readonly Config $config,
    private readonly Database $database,
    private readonly View $view,
    private readonly Auth $auth,
    private readonly Csrf $csrf,
            
		
#### $request = Request -> capture define una instancia de Request con
    private readonly array $get,
    private readonly array $post,
    private readonly array $server,
	
#### define array $app->routes[] con rutas. Cada ruta contiene [metodo_http, path, handler]
    $app->get('/', [DashboardController::class, 'index']);
    $app->get('/login', [AuthController::class, 'showLogin']);
    $app->post('/login', [AuthController::class, 'login']);
    $app->post('/logout', [AuthController::class, 'logout']);

    $app->get('/personas', [PersonaController::class, 'index']);
    $app->get('/personas/{id}/alumno', [AlumnoController::class, 'show']);
    $app->post('/personas/{id}', [AlumnoController::class, 'updatePersona']);
    $app->post('/personas/{id}/alumno', [AlumnoController::class, 'saveAlumno']);
    $app->post('/personas/{id}/alumno/comisiones', [AlumnoController::class, 'saveComisiones']);
    $app->get('/comisiones/buscar', [AlumnoController::class, 'searchComisiones']);

    $app->get('/comisiones', [ComisionController::class, 'index']);


#### $app->dispatch($request);  
	

    //utilizando la libreria FastRoute instalada via composer construye el ruteador, carga el conjunto de rutas definidas en el index
        /** @var Dispatcher */ $dispatcher = simpleDispatcher(function (RouteCollector $router): void {
            foreach ($this->routes as [$method, $path, $handler]) {
                $router->addRoute($method, $path, $handler);
            }
        });
        
    //llama a Dispatchet->dispatch -> documentacion oficial $params $httpMethod, $uri. Returns array 
        [self::NOT_FOUND]
        [self::METHOD_NOT_ALLOWED, ['GET', 'OTHER_ALLOWED_METHODS']]
        [self::FOUND, $handler, ['varName' => 'value', ...]]
        
        
    $routeInfo = $dispatcher->dispatch($request->method(), $request->path($this->config->basePath()));
        
    

    [, $handler, $vars] = $routeInfo;
        Si corre en /Fines2/fines-standalone/public y la url es /Fines2/fines-standalone/public/personas, $request->path devuelve personas. Buscando en su ruteador previamente define el handler dentro de routeinfo  
        Array
        (
            [0] => FinesApp\Controllers\PersonaController
            [1] => index
        )
        $vars se completa por ejemplo cuando la url tiene mas parametros  http://localhost/Fines2/fines-standalone/public/personas/5AB9720AD6BCD/alumno
            [1] => Array
                (
                    [0] => FinesApp\Controllers\AlumnoController
                    [1] => show
                )

            [2] => Array
                (
                    [id] => 5AB9720AD6BCD
                )			
    [$controllerClass, $method] = $handler;
    $controller = new $controllerClass($this->database->pdo(), $this->view, $this->auth, $this->csrf);
    $controller->{$method}($request, $vars);

	
	
