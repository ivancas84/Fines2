# Fines Standalone

Aplicacion PHP liviana para administrar la base Fines sin depender de WordPress.

## Stack

- PHP 8.2+
- Composer
- PDO
- FastRoute
- Templates PHP propios
- HTMX para interacciones parciales
- Bootstrap 5

## Instalacion local

```powershell
cd C:\xampp\htdocs\Fines2\fines-standalone
composer install
```

Copiar `.env.example` a `.env` y ajustar la conexion si hace falta. La configuracion local actual apunta a:

```ini
DB_DATABASE=planfi10_20204
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8
```

Las constancias se administran en un sistema externo e independiente. Configurar solamente su URL publica:

```ini
CONSTANCIAS_PUBLIC_URL=https://abcconstancias.com.ar
```

Desde la ficha del alumno, `fines-standalone` abre los formularios de `abcconstancias.com.ar` y envía los datos iniciales mediante parámetros de la URL. No genera PDFs, no guarda constancias y no accede al almacenamiento del otro sistema.

Crear las tablas de login:

```powershell
Get-Content database\login_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

El acceso se realiza exclusivamente mediante Google OAuth. El correo de Google debe existir en `fines_app_users`, estar activo y tener `establecimiento_id = 1`.

Para habilitar un usuario:

```sql
INSERT INTO fines_app_users (establecimiento_id, nombre, email, password_hash, rol, activo)
VALUES (1, 'Nombre Apellido', 'correo@gmail.com', NULL, 'operador', 1);
```

En instalaciones antiguas donde `password_hash` todavía sea obligatorio, usar una cadena vacía en lugar de `NULL`. La aplicación no consulta esa columna.

## URL local con XAMPP

```text
http://localhost/Fines2/fines-standalone/public/login
```

## Tablas propias

La app agrega tablas con prefijo `fines_app_`:

- `fines_app_users`: usuarios, password hash, rol y estado.
- `fines_app_audit_logs`: preparada para registrar acciones futuras.

Los datos de dominio siguen usando las tablas Fines existentes, por ejemplo `persona`, `alumno`, `comision`, `calendario`, `plan`, `alumno_comision`, `calificacion`.

Las tablas históricas `fines_app_establecimientos` y `fines_app_constancias`, si ya existen en una base instalada, no son utilizadas por esta aplicación y pueden conservarse hasta decidir su eliminación manual.

## Integración con ProgramaFines

Configurar en `.env` el identificador interno del período vigente:

```ini
PROGRAMAFINES_PERIOD=6
```

Cada usuario conecta su sesión desde la pantalla **ProgramaFines** copiando el valor de la cookie `PHPSESS`.
El valor queda solamente en la sesión PHP del navegador y se elimina al desconectar o cerrar sesión.
