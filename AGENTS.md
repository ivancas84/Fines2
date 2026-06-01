# AGENTS.md

## Proyecto
Este proyecto consiste en un nuevo sistema de administración `fines-standalone` para reemplazar un plugin de WordPress. El código existente se usa como referencia histórica y funcional, pero el nuevo sistema debe ser autocontenido.

## Estructura principal
- `fines-standalone/`: aplicación nueva. Es el destino principal para nuevas pantallas, flujos y lógica de administración.
- `wp/`: código WordPress del proyecto. Tratar carpetas existentes como referencia histórica, no como dependencia para nuevas funcionalidades.
- `script/`: scripts PHP independientes; usarlos como referencia de procesos existentes, no como dependencia directa para `fines-standalone`.
- `src/Fines2/`: clases y DAOs para acceder a la base Fines; referencia útil para entender reglas actuales, no base técnica de `fines-standalone`.
- `src/Pedidos/`: clases para acceder a tablas del plugin SupportCandy en la base WordPress. El prefijo de esas tablas es `wpwt_psmsc`. Será reemplazado por nuevas tablas en la base de datos utilizada por `fines-standalone`.
- `src/ProgramaFines/`: clases para interactuar desde PHP con una web de administración externa.
- `src/SqlOrganize/` y `src/SqlOrganizeMy/`: código auxiliar para automatizar schemas, modelos y acceso a datos.
- `doc/fines.sql`: estructura y datos principales de administración Fines. Será la base principal de `fines-standalone`, a la cual se agregarán nuevas tablas para cumplir con los requerimientos solicitados.
- `doc/wordpress.sql`: base WordPress, incluyendo tablas de SupportCandy, se usa como referencia.
- `doc/cambios_wordpress.sql`: ajustes manuales aplicados sobre la base WordPress original.

## Stack de fines-standalone
- PHP 8.2+ con `declare(strict_types=1)`.
- Composer con autoload PSR-4 `FinesApp\` apuntando a `app/`.
- PDO para acceso a MySQL/MariaDB.
- `nikic/fast-route` para ruteo HTTP.
- `vlucas/phpdotenv` para configuración por `.env`.
- Templates PHP propios en `resources/views`.
- Bootstrap 5 y HTMX desde CDN para la interfaz.
- JavaScript y CSS propios en `public/assets`.

## Estructura de fines-standalone
- `public/index.php`: front controller y definición de rutas.
- `app/Core/`: infraestructura liviana de la aplicación (`App`, `Request`, `Response`, `View`, `Database`, `Auth`, `Csrf`, `Session`, `Config`).
- `app/Controllers/`: controladores HTTP. Deben coordinar request, validación, repositorios y vistas.
- `app/Repositories/`: consultas a base de datos. Preferir concentrar aquí el SQL de dominio.
- `app/Support/helpers.php`: helpers globales de presentación/rutas.
- `resources/views/`: vistas PHP. Usar layouts y parciales propios del standalone.
- `database/`: scripts SQL propios del standalone, por ejemplo tablas de login.
- `storage/`: artefactos locales generados o de apoyo, como capturas.
- `vendor/`: dependencias Composer; no editar manualmente.

## Instalación y ejecución local
Trabajar desde:

```powershell
cd C:\xampp\htdocs\Fines2\fines-standalone
composer install
```

Copiar `.env.example` a `.env` y ajustar la conexión si hace falta. La URL local esperada con XAMPP es:

```text
http://localhost/Fines2/fines-standalone/public/login
```

Para crear tablas propias de login:

```powershell
Get-Content database\login_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

## Base de datos
- La base principal actual es la de Fines documentada en `doc/fines.sql`.
- `fines-standalone` agrega tablas propias con prefijo `fines_app_`.
- No crear nuevas dependencias sobre tablas WordPress o SupportCandy. Si se necesita migrar una funcionalidad, usar `doc/wordpress.sql`, `doc/cambios_wordpress.sql` y `src/Pedidos/` solo para entender el modelo anterior.
- Para nuevas consultas usar PDO con prepared statements.
- Mantener el SQL de acceso a datos en repositorios cuando sea posible.

## Dirección técnica
Construir `fines-standalone` como sistema nuevo y autocontenido. Usar el código anterior solo para entender reglas de negocio, nombres de tablas, campos y flujos esperados.

No enlazar pantallas nuevas a slugs, scripts o formularios anteriores. Si una funcionalidad todavía no existe en `fines-standalone`, dejarla fuera o crear su pantalla propia dentro de `fines-standalone`.

Para nuevas pantallas:
- Definir la ruta en `fines-standalone/public/index.php`.
- Crear o extender un controller en `app/Controllers`.
- Crear o extender un repository en `app/Repositories` para consultas.
- Crear vistas en `resources/views`, reutilizando `resources/views/layouts/app.php`.
- Usar helpers existentes como `e()` y `url()` para salida HTML y rutas.
- Proteger formularios POST con CSRF.
- Mantener la autenticación del standalone; no depender de sesiones ni usuarios WordPress.

## Convenciones de implementación
- Mantener `declare(strict_types=1)` en archivos PHP nuevos.
- Usar namespaces bajo `FinesApp\`.
- Preferir código simple y explícito antes que introducir frameworks adicionales.
- Evitar lógica de negocio compleja dentro de las vistas.
- No editar `vendor/`; actualizar dependencias con Composer si alguna vez hace falta.
- No guardar credenciales reales en archivos versionados. Usar `.env` para configuración local.
- Al tocar frontend, respetar el estilo existente basado en Bootstrap 5, `public/assets/app.css` y HTMX para interacciones parciales.

## Reglas de trabajo
- No revertir cambios locales que no hayas hecho.
- Implementar pantallas y flujos en `fines-standalone` sin depender de pantallas, scripts ni slugs anteriores.
- Avanzar por pantallas o flujos pequeños para que el usuario pueda entender y validar cada paso.
- Antes de reutilizar código viejo, verificar que no arrastre dependencias de WordPress, SupportCandy o rutas históricas.
