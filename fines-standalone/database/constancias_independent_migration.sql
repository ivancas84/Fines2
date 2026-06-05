ALTER TABLE fines_app_constancias
    ADD COLUMN establecimiento_id BIGINT UNSIGNED NULL AFTER id,
    ADD COLUMN nombres VARCHAR(120) NULL AFTER clave,
    ADD COLUMN apellidos VARCHAR(120) NULL AFTER nombres,
    ADD COLUMN numero_documento VARCHAR(40) NULL AFTER apellidos,
    ADD COLUMN datos_json JSON NULL AFTER numero_documento,
    ADD COLUMN origen_sistema VARCHAR(80) NULL AFTER datos_json,
    ADD COLUMN origen_referencia VARCHAR(120) NULL AFTER origen_sistema;

UPDATE fines_app_constancias c
LEFT JOIN persona p ON p.id = c.persona_id
SET c.nombres = COALESCE(NULLIF(p.nombres, ''), 'Sin nombre'),
    c.apellidos = COALESCE(NULLIF(p.apellidos, ''), 'Sin apellido'),
    c.numero_documento = COALESCE(NULLIF(p.numero_documento, ''), ''),
    c.datos_json = JSON_OBJECT(
        'persona_id', c.persona_id,
        'alumno_id', c.alumno_id
    ),
    c.origen_sistema = 'fines-standalone',
    c.origen_referencia = CONCAT('alumno:', c.alumno_id);

UPDATE fines_app_constancias
SET archivo_path = SUBSTRING(archivo_path, CHAR_LENGTH('storage/constancias/') + 1)
WHERE archivo_path LIKE 'storage/constancias/%';

ALTER TABLE fines_app_constancias
    MODIFY nombres VARCHAR(120) NOT NULL,
    MODIFY apellidos VARCHAR(120) NOT NULL,
    MODIFY numero_documento VARCHAR(40) NOT NULL,
    DROP INDEX fines_app_constancias_persona_index,
    DROP INDEX fines_app_constancias_alumno_index,
    DROP COLUMN persona_id,
    DROP COLUMN alumno_id,
    ADD KEY fines_app_constancias_documento_index (numero_documento),
    ADD KEY fines_app_constancias_origen_index (origen_sistema, origen_referencia),
    ADD KEY fines_app_constancias_establecimiento_index (establecimiento_id);
