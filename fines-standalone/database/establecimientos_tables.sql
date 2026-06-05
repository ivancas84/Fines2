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
    ADD COLUMN IF NOT EXISTS logo_path VARCHAR(255) NULL AFTER direccion;
