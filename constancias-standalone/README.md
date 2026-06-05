# Constancias Standalone

Sistema independiente para emitir, validar y descargar constancias PDF.

## Instalacion local

```powershell
cd C:\xampp\htdocs\Fines2\constancias-standalone
composer install
Copy-Item .env.example .env
Get-Content database\schema.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```

URL local:

```text
http://localhost/Fines2/constancias-standalone/public/login
```

Usuario inicial:

```text
email: admin@example.com
contrasena: admin123
```

## Storage

Los PDF e imagenes se guardan bajo `CONSTANCIAS_STORAGE_PATH`. En la base se guarda solo la ruta relativa del archivo.

Si la base ya tenia tablas `fines_app_*`, aplicar tambien:

```powershell
Get-Content database\upgrade_existing.sql | & 'C:\xampp\mysql\bin\mysql.exe' -uroot planfi10_20204
```
