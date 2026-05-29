# AGENTS.md

## Proyecto
Este proyecto es principalmente un plugin de WordPress para administracion de Fines. La direccion actual es construir `Fines7` como plugin nuevo, tomando el codigo existente solo como referencia funcional y de dominio.

## Estructura principal
- `wp/`: codigo WordPress del proyecto. Tratar carpetas existentes como referencia historica, no como dependencia para nuevas funcionalidades.
- `wp/fines7/`: plugin principal nuevo. Debe priorizar PDO directo y evitar depender de clases generadas antiguas o pantallas previas.
- `script/`: scripts PHP independientes; usarlos como referencia de procesos existentes, no como dependencia directa para Fines7.
- `src/Fines2/`: clases y DAOs para acceder a la base Fines; referencia util para entender reglas actuales, no base tecnica de Fines7.
- `src/Pedidos/`: clases para acceder a tablas del plugin SupportCandy en la base WordPress. El prefijo de esas tablas es `wpwt_psmsc`.
- `src/ProgramaFines/`: clases para interactuar desde PHP con una web de administracion externa.
- `src/SqlOrganize/` y `src/SqlOrganizeMy/`: codigo auxiliar para automatizar schemas, modelos y acceso a datos. No usarlo en Fines7 salvo pedido explicito.
- `doc/fines.sql`: estructura y datos principales de administracion Fines.
- `doc/wordpress.sql`: base WordPress, incluyendo tablas de SupportCandy.
- `doc/cambios_wordpress.sql`: ajustes manuales aplicados sobre la base WordPress original.

## Direccion tecnica
Construir `Fines7` como sistema nuevo y autocontenido. Usar el codigo anterior solo para entender reglas de negocio, nombres de tablas, campos y flujos esperados.

No enlazar pantallas nuevas a slugs, scripts o formularios anteriores. Si una funcionalidad todavia no existe en `Fines7`, dejarla fuera o crear su pantalla propia dentro de `Fines7`.

Para nuevas pantallas, preferir consultas preparadas con `PDO`, repositorios pequenos y vistas WordPress escapadas con `esc_html`, `esc_attr` y `esc_url`.

Fines7 es instalable como carpeta autocontenida en `wp-content/plugins/fines7`. Carga clases y librerias mediante el Composer propio ubicado en `wp/fines7/composer.json`; ejecutar `composer install` dentro de esa carpeta para generar `wp/fines7/vendor/`.

## Base de datos
No asumir que existe una base MySQL local disponible. El proyecto puede estar ejecutandose en un servidor externo. Si una tarea requiere validar datos reales, pedir o usar una copia de la base antes de concluir comportamiento dependiente de datos.

Fines7 usa configuracion propia en `wp/fines7/config/fines7.php`, que es el archivo real y no se versiona. Usar `wp/fines7/config/fines7.example.php` como plantilla versionada.

## Reglas de trabajo
- No revertir cambios locales que no hayas hecho.
- Implementar pantallas y flujos en `Fines7` sin depender de pantallas, scripts ni slugs anteriores.
- Avanzar por pantallas o flujos pequenos para que el usuario pueda entender y validar cada paso.
- Para WordPress admin, respetar permisos, nonces en formularios mutantes y escapes de salida.
