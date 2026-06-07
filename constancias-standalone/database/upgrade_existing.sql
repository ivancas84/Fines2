CREATE TABLE IF NOT EXISTS fines_app_establecimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(190) NOT NULL,
    cue VARCHAR(40) NULL,
    direccion VARCHAR(255) NULL,
    logo_path VARCHAR(255) NULL,
    firma_director_path VARCHAR(255) NULL,
    sello_oval_path VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE fines_app_users
    ADD COLUMN IF NOT EXISTS establecimiento_id BIGINT UNSIGNED NULL AFTER id,
    ADD KEY IF NOT EXISTS fines_app_users_establecimiento_index (establecimiento_id);

ALTER TABLE fines_app_establecimientos
    ADD COLUMN IF NOT EXISTS localidad VARCHAR(120) NULL DEFAULT 'La Plata' AFTER nombre,
    ADD COLUMN IF NOT EXISTS modalidad_principal VARCHAR(190) NULL DEFAULT 'Programa Fines 2 Trayecto Secundario' AFTER localidad,
    ADD COLUMN IF NOT EXISTS orientacion_principal VARCHAR(190) NULL DEFAULT 'Ciencias Sociales' AFTER modalidad_principal,
    ADD COLUMN IF NOT EXISTS resolucion_principal VARCHAR(80) NULL DEFAULT '2993/22' AFTER orientacion_principal,
    ADD COLUMN IF NOT EXISTS logo_path VARCHAR(255) NULL AFTER direccion;

UPDATE fines_app_establecimientos
SET localidad = 'La Plata'
WHERE localidad IS NULL OR localidad = '' OR localidad = direccion;

UPDATE fines_app_establecimientos
SET modalidad_principal = COALESCE(NULLIF(modalidad_principal, ''), 'Programa Fines 2 Trayecto Secundario'),
    orientacion_principal = COALESCE(NULLIF(orientacion_principal, ''), 'Ciencias Sociales'),
    resolucion_principal = COALESCE(NULLIF(resolucion_principal, ''), '2993/22');

ALTER TABLE fines_app_constancias
    ADD COLUMN IF NOT EXISTS establecimiento_id BIGINT UNSIGNED NULL AFTER id,
    ADD COLUMN IF NOT EXISTS nombres VARCHAR(120) NULL AFTER clave,
    ADD COLUMN IF NOT EXISTS apellidos VARCHAR(120) NULL AFTER nombres,
    ADD COLUMN IF NOT EXISTS numero_documento VARCHAR(40) NULL AFTER apellidos,
    ADD COLUMN IF NOT EXISTS datos_json JSON NULL AFTER numero_documento,
    ADD KEY IF NOT EXISTS fines_app_constancias_documento_index (numero_documento),
    ADD KEY IF NOT EXISTS fines_app_constancias_establecimiento_index (establecimiento_id);

ALTER TABLE fines_app_constancias
    DROP INDEX IF EXISTS fines_app_constancias_origen_index,
    DROP COLUMN IF EXISTS origen_sistema,
    DROP COLUMN IF EXISTS origen_referencia;
