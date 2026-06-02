# Flujo principal

## index.php
	
### $app = App -> boot define una instancia de App con
    private readonly string $basePath,
    private readonly Config $config,
    private readonly Database $database,
    private readonly View $view,
    private readonly Auth $auth,
    private readonly Csrf $csrf,
            
		
### $request = Request -> capture define una instancia de Request con
    private readonly array $get,
    private readonly array $post,
    private readonly array $server,
	
### define array $app->routes[] con rutas. Cada ruta contiene [metodo_http, path, handler]
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


### $app->dispatch($request);  
	

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

	
	

# Autocompletar de comisión

## vista html

```html
<div class="comision-picker">
	<input class="comision-search" value="<?= e($comision['pfid'] ?? '') ?>" placeholder="PFID o ID" autocomplete="off">
	<input class="comision-id" type="hidden" name="comision_ref[<?= e($index) ?>]" value="<?= e($comision['comision_id']) ?>">
	<div class="comision-summary"><?= e($comisionSummary($comision)) ?></div>
	<div class="comision-results" hidden></div>
</div>

comision-search: escritura de pfid e id
comision-id: id real de la comisión seleccionada
comision-summary: Texto descriptivo: PFID, sede, período, tramo y plan.
comision-results: es el contenedor flotante donde aparecen las opciones.


name="comision_ref[<?= e($index) ?>]" en filas existentes, es decir el nombre del input para el alumno_comision existente es alumno_comision0, alumno_comision1, etc
name="new_comision_ref" en nuevas filas, es decir el nombre del input para el alumno_comision nuevo es new_comision_ref

```

## javascript del autocompletar
### al cargar la pagina busca todos los comision-picker

document.querySelectorAll('.comision-picker').forEach((wrapper) => { 

### Para cada picker, toma sus piezas internas:
const input = wrapper.querySelector('.comision-search');
const hidden = wrapper.querySelector('.comision-id');
const results = wrapper.querySelector('.comision-results');

### Cuando escribís o enfocás el input, se ejecuta search, 
Con debounce de 250 ms.
Si hay 3 o más caracteres, hace un fetch a ruta definida en index.php (line 27): $app->get('/comisiones/buscar', [AlumnoController::class, 'searchComisiones']);

El controller recibe esa búsqueda en AlumnoController.php (line 128), exige login y responde JSON: Response::json((new AlumnoComisionRepository($this->pdo))->search((string) $request->query('q', ''), 10));

El repositorio busca en la tabla comision por id o pfid. También une sede, calendario, planificacion y plan para poder mostrar el texto completo. Cada resultado vuelve con esta forma:
[
    'id' => ...,
    'pfid' => ...,
    'summary' => 'PFID ... | Sede ... | Periodo ... | Tramo ... | Plan ...',
    'label' => mismo texto
]

El JS recibe ese JSON y crea botones dentro de .comision-results.

Cuando hacés click en una opción:

input.value = row.pfid || row.id;
hidden.value = row.id;
summary.textContent = row.summary || row.label;
clearResults(results);

### Cuando apretás Guardar comisiones, el formulario manda:

alumno_comision_id[...]
comision_ref[...]
estado[...]
activo[...]
new_comision_ref
new_estado
new_activo

El controller arma las filas en AlumnoController.php (line 154).
Después el repositorio guarda en AlumnoComisionRepository.php (line 101):


### Codigo javascript completo

```js
(function () {
    const debounce = (callback, wait) => {
        let timer = null;
        return (...args) => {
            window.clearTimeout(timer);
            timer = window.setTimeout(() => callback(...args), wait);
        };
    };

    const clearResults = (results) => {
        results.innerHTML = '';
        results.hidden = true;
    };

    const renderResults = (wrapper, rows) => {
        const input = wrapper.querySelector('.comision-search');
        const hidden = wrapper.querySelector('.comision-id');
        const summary = wrapper.querySelector('.comision-summary');
        const results = wrapper.querySelector('.comision-results');
        clearResults(results);

        if (!rows.length) {
            const empty = document.createElement('div');
            empty.className = 'comision-empty';
            empty.textContent = 'Sin resultados';
            results.appendChild(empty);
            results.hidden = false;
            return;
        }

        rows.forEach((row) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'comision-option';
            button.textContent = row.label;
            button.addEventListener('click', () => {
                input.value = row.pfid || row.id;
                hidden.value = row.id;
                summary.textContent = row.label;
                clearResults(results);
            });
            results.appendChild(button);
        });

        results.hidden = false;
    };

    document.querySelectorAll('.comision-picker').forEach((wrapper) => {
        const input = wrapper.querySelector('.comision-search');
        const hidden = wrapper.querySelector('.comision-id');
        const results = wrapper.querySelector('.comision-results');
        if (!input || !hidden || !results) {
            return;
        }

        const search = debounce(async () => {
            const q = input.value.trim();
            hidden.value = '';
            if (q.length < 3) {
                clearResults(results);
                return;
            }

            try {
                const baseUrl = document.body.dataset.baseUrl || '/';
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/comisiones/buscar?q=${encodeURIComponent(q)}`, {
                    credentials: 'same-origin',
                });
                renderResults(wrapper, await response.json());
            } catch (error) {
                clearResults(results);
            }
        }, 250);

        input.addEventListener('input', search);
        input.addEventListener('focus', search);
    });

    document.addEventListener('click', (event) => {
        document.querySelectorAll('.comision-picker').forEach((wrapper) => {
            if (!wrapper.contains(event.target)) {
                const results = wrapper.querySelector('.comision-results');
                if (results) {
                    clearResults(results);
                }
            }
        });
    });
}());
```