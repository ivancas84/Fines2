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

Para que las constancias se guarden en el storage del sistema Constancias, configurar:

```ini
CONSTANCIAS_STORAGE_PATH=C:\xampp\htdocs\Fines2\constancias-standalone\storage\constancias
CONSTANCIAS_PUBLIC_URL=http://localhost/Fines2/constancias-standalone/public
```

`CONSTANCIAS_STORAGE_PATH` es una ruta de servidor compartida. En la base se guarda solo la ruta relativa del PDF, por ejemplo `2026/06/constancia_123_alumno_regular_37379203.pdf`.

Crear las tablas de login:

```powershell
Get-Content database\login_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

Si ya existian las tablas de login, agregar establecimientos y la relacion del usuario:

```powershell
Get-Content database\establecimientos_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

Crear las tablas de constancias:

```powershell
Get-Content database\constancias_tables.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

Si la tabla fue creada con la primera version acoplada a Fines, migrarla al modelo independiente:

```powershell
Get-Content database\constancias_independent_migration.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
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
- `fines_app_establecimientos`: escuela asociada al usuario, firma del director y sello oval.
- `fines_app_audit_logs`: preparada para registrar acciones futuras.
- `fines_app_constancias`: constancias emitidas, datos snapshot del titular, origen opcional, clave publica de validacion y ruta relativa del PDF.

Los datos de dominio siguen usando las tablas Fines existentes, por ejemplo `persona`, `alumno`, `comision`, `calendario`, `plan`, `alumno_comision`, `calificacion`.
