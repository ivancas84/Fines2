# AGENTS.md

## Proyecto
Este proyecto consiste en un nuevo sistema de administracion `fines-standalone` para reemplazar un plugin de wordpress. Se tomará el código existente.

## Estructura principal
- `wp/`: codigo WordPress del proyecto. Tratar carpetas existentes como referencia historica, no como dependencia para nuevas funcionalidades.
- `script/`: scripts PHP independientes; usarlos como referencia de procesos existentes, no como dependencia directa para fines-standalone.
- `src/Fines2/`: clases y DAOs para acceder a la base Fines; referencia util para entender reglas actuales, no base tecnica de fines-standalone.
- `src/Pedidos/`: clases para acceder a tablas del plugin SupportCandy en la base WordPress. El prefijo de esas tablas es `wpwt_psmsc`. Será reemplazado por nuevas tablas en la base de datos de utilizada para fines-standalone.
- `src/ProgramaFines/`: clases para interactuar desde PHP con una web de administracion externa.
- `src/SqlOrganize/` y `src/SqlOrganizeMy/`: codigo auxiliar para automatizar schemas, modelos y acceso a datos.
- `doc/fines.sql`: estructura y datos principales de administracion Fines. Será la base principal de fines-standalone a la cual se agregarán nuevas tablas para cumplir con los requerimientos solicitados.
- `doc/wordpress.sql`: base WordPress, incluyendo tablas de SupportCandy, se usaran como referencia.
- `doc/cambios_wordpress.sql`: ajustes manuales aplicados sobre la base WordPress original.

## Direccion tecnica
Construir `fines-standalone` como sistema nuevo y autocontenido. Usar el codigo anterior solo para entender reglas de negocio, nombres de tablas, campos y flujos esperados.

No enlazar pantallas nuevas a slugs, scripts o formularios anteriores. Si una funcionalidad todavia no existe en `fines-standalone`, dejarla fuera o crear su pantalla propia dentro de `fines-standalone`.

Para nuevas pantallas, preferir consultas preparadas con `PDO`.

## Reglas de trabajo
- No revertir cambios locales que no hayas hecho.
- Implementar pantallas y flujos en `fines-standalone` sin depender de pantallas, scripts ni slugs anteriores.
- Avanzar por pantallas o flujos pequenos para que el usuario pueda entender y validar cada paso.