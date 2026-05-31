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

Crear las tablas de login:

```powershell
Get-Content database\login_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

Usuario inicial de desarrollo:

```text
email: admin@example.com
contrasena: admin123
```

## URL local con XAMPP

```text
http://localhost/Fines2/fines-standalone/public/login
```

## Tablas propias

La app agrega tablas con prefijo `fines_app_`:

- `fines_app_users`: usuarios, password hash, rol y estado.
- `fines_app_audit_logs`: preparada para registrar acciones futuras.

Los datos de dominio siguen usando las tablas Fines existentes, por ejemplo `persona`, `alumno`, `comision`, `calendario`, `plan`, `alumno_comision`, `calificacion`.
