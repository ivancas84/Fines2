# Constancias Standalone

Sistema independiente para emitir, validar y descargar constancias PDF.

## Instalacion local

```powershell
cd C:\xampp\htdocs\Fines2\constancias-standalone
composer install
Copy-Item .env.example .env
Get-Content database\schema.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

Si el servidor no permite ejecutar Composer, subir tambien el directorio `vendor/` completo.

URL local:

```text
http://localhost/Fines2/constancias-standalone/public/login
```

Usuario inicial habilitado para Google:

```text
email: admin@example.com
```

Cambiar ese email en `fines_app_users` por la cuenta de Google autorizada antes de iniciar sesion.

## Storage

Los PDF e imagenes se guardan bajo `CONSTANCIAS_STORAGE_PATH`. En la base se guarda solo la ruta relativa del archivo.
